<?php

/**
 * Exercise CostControl live-rate success, rejection and scheduling paths against Kanboard.
 *
 * Related files: Plugin.php, Console/RefreshLiveRatesCommand.php,
 * Controller/CostController.php, Model/ExtendedCurrencyModel.php and
 * tests/Fixture/FakeHttpClient.php.
 */

if ($argc !== 2 || !is_file($argv[1] . '/app/common.php')) {
    fwrite(STDERR, 'Usage: php tests/live_rate_integration.php /path/to/kanboard' . PHP_EOL);
    exit(2);
}

$kanboardRoot = realpath($argv[1]);
$databaseFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cost-control-live-rates-' . bin2hex(random_bytes(8)) . '.sqlite';
$failures = array();

define('PLUGINS_DIR', dirname(__DIR__, 2));
define('DB_FILENAME', $databaseFile);

chdir($kanboardRoot);
require $kanboardRoot . '/app/common.php';
require_once __DIR__ . '/Fixture/FakeHttpClient.php';

$loadedPlugins = $container['pluginLoader']->getPlugins();

if (!isset($loadedPlugins['CostControl'])) {
    $failures[] = 'CostControl did not load in the disposable Kanboard instance.';
} else {
    $httpClient = new \Kanboard\Plugin\CostControl\Tests\Fixture\FakeHttpClient();
    $container['httpClient'] = $httpClient;
    $currencyModel = $container['currencyModel'];
    $now = time();

    $container['configModel']->save(array('application_currency' => 'USD'));
    $providerRates = array();

    foreach ($currencyModel->getCurrencies() as $currency => $label) {
        if ($currency !== 'XBT') {
            $providerRates[$currency] = $currency === 'USD' ? 1 : 1.25;
        }
    }

    $providerRates['EUR'] = 0.91;
    $providerRates['GBP'] = 0.78;
    $validPayload = array(
        'result' => 'success',
        'base_code' => 'USD',
        'time_last_update_unix' => $now - 3600,
        'time_next_update_unix' => $now + 3600,
        'rates' => $providerRates,
    );
    $httpClient->setPayload($validPayload);

    if (!$currencyModel->getLiveRates()) {
        $failures[] = 'A valid provider response was rejected.';
    }

    $eur = $currencyModel->getByCurrency('EUR');

    if ((float) $eur['live_rate'] !== 0.91 || (int) $eur['live_rate_updated'] !== $now - 3600) {
        $failures[] = 'A valid provider response was not stored with its source timestamp.';
    }

    if ($currencyModel->isLiveRateRefreshDue($now)) {
        $failures[] = 'The next-update guard did not suppress an early repeat request.';
    }

    $container['cli']->setAutoExit(false);
    $commandExitCode = $container['cli']->run(
        new \Symfony\Component\Console\Input\ArrayInput(
            array(
                'command' => 'cost-control:refresh-rates',
                '--force' => true,
            )
        ),
        new \Symfony\Component\Console\Output\BufferedOutput()
    );

    if ($commandExitCode !== 0) {
        $failures[] = 'The forced live-rate refresh command did not complete successfully.';
    }

    $lastGoodRate = $currencyModel->getLiveRate('EUR');
    $lastGoodUpdate = $container['configModel']->getOption('cost_control_last_updated');
    $invalidPayload = $validPayload;
    $invalidPayload['base_code'] = 'GBP';
    $invalidPayload['rates']['EUR'] = 99;
    $httpClient->setPayload($invalidPayload);

    if ($currencyModel->getLiveRates()) {
        $failures[] = 'A response for the wrong base currency was accepted.';
    }

    if (
        $currencyModel->getLiveRate('EUR') != $lastGoodRate
        || $container['configModel']->getOption('cost_control_last_updated') != $lastGoodUpdate
    ) {
        $failures[] = 'An invalid response replaced previously validated rate data.';
    }

    $truncatedPayload = $validPayload;
    unset($truncatedPayload['rates']['EUR']);
    $httpClient->setPayload($truncatedPayload);

    if ($currencyModel->getLiveRates()) {
        $failures[] = 'A truncated provider response was accepted.';
    }

    $httpClient->setException(new RuntimeException('Expected provider failure'));

    if ($currencyModel->getLiveRates()) {
        $failures[] = 'A provider exception was reported as a successful refresh.';
    }

    if (!$container['cli']->has('cost-control:refresh-rates')) {
        $failures[] = 'The live-rate refresh command was not registered.';
    }
}

$container['db']->closeConnection();

if (is_file($databaseFile)) {
    unlink($databaseFile);
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl live-rate Kanboard integration passed.' . PHP_EOL;
