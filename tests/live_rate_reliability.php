<?php

/**
 * Verify CostControl live-rate scheduling, validation and failure-safety contracts.
 *
 * Related files: Plugin.php, Console/RefreshLiveRatesCommand.php,
 * Controller/CostController.php, Model/ExtendedCurrencyModel.php,
 * Template/currency/show.php, tests/live_rate_integration.php,
 * tests/Fixture/FakeHttpClient.php, INSTALL.md and README.md.
 */

$pluginRoot = dirname(__DIR__);
$plugin = file_get_contents($pluginRoot . '/Plugin.php');
$command = file_get_contents($pluginRoot . '/Console/RefreshLiveRatesCommand.php');
$controller = file_get_contents($pluginRoot . '/Controller/CostController.php');
$currencyModel = file_get_contents($pluginRoot . '/Model/ExtendedCurrencyModel.php');
$currencyList = file_get_contents($pluginRoot . '/Template/currency/show.php');
$installation = file_get_contents($pluginRoot . '/INSTALL.md');
$readme = file_get_contents($pluginRoot . '/README.md');
$failures = array();

if (strpos($plugin, 'file_get_contents(') !== false || strpos($plugin, 'getLiveRates();') !== false) {
    $failures[] = 'Plugin bootstrap still performs a live-rate provider request.';
}

foreach (
    array(
        'new RefreshLiveRatesCommand($this->container)',
        "setName('cost-control:refresh-rates')",
        "addOption('force'",
        'refreshLiveRatesIfDue();',
        'cost_control_next_live_rate_attempt',
        'LIVE_RATE_FAILURE_RETRY = 900',
        'httpClient->getJson(',
        'catch (\\Throwable $exception)',
        'isValidLiveRatePayload($payload, $baseCurrency)',
        "currency !== 'XBT' && !isset(\$payload['rates'][\$currency])",
        'db->cancelTransaction()',
        "cost_control_last_checked_live_rates",
        './cli cost-control:refresh-rates',
    ) as $snippet
) {
    $sources = $plugin . $command . $controller . $currencyModel . $currencyList . $installation . $readme;

    if (strpos($sources, $snippet) === false) {
        $failures[] = 'Missing live-rate reliability contract: ' . $snippet;
    }
}

if (strpos($readme, '`XBT` remains available for manual rates') === false) {
    $failures[] = 'The unsupported XBT live-rate boundary is not documented.';
}

if (strpos($currencyModel, 'file_get_contents(') !== false) {
    $failures[] = 'The live-rate model still bypasses Kanboard HTTP safeguards.';
}

if (strpos($currencyList, "get('last_checked_liverates'") !== false) {
    $failures[] = 'The live-rate view still reads the obsolete last-checked key.';
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl live-rate reliability contract passed.' . PHP_EOL;
