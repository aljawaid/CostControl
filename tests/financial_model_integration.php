<?php

/**
 * Exercise CostControl financial calculations and validation against Kanboard.
 *
 * Related files: Model/Budget.php, Model/HourlyRate.php and
 * Model/ExtendedCurrencyModel.php.
 */

if ($argc !== 2 || !is_file($argv[1] . '/app/common.php')) {
    fwrite(STDERR, 'Usage: php tests/financial_model_integration.php /path/to/kanboard' . PHP_EOL);
    exit(2);
}

$kanboardRoot = realpath($argv[1]);
$databaseFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cost-control-financial-' . bin2hex(random_bytes(8)) . '.sqlite';
$failures = array();

define('PLUGINS_DIR', dirname(__DIR__, 2));
define('DB_FILENAME', $databaseFile);

chdir($kanboardRoot);
require $kanboardRoot . '/app/common.php';

$loadedPlugins = $container['pluginLoader']->getPlugins();

if (!isset($loadedPlugins['CostControl'])) {
    $failures[] = 'CostControl did not load in the disposable Kanboard instance.';
} else {
    $projectId = $container['projectModel']->create(array('name' => 'Financial precision test'));
    $userId = $container['userModel']->create(array(
        'username' => 'cost-control-financial-test',
        'password' => 'not-used-for-login',
    ));
    $today = date('Y-m-d');

    if (!$projectId || !$userId) {
        $failures[] = 'Unable to create disposable Kanboard records.';
    } else {
        $container['budget']->create($projectId, '10.1250014', 'first', $today);
        $container['budget']->create($projectId, '0.1250006', 'second', $today);

        if ($container['budget']->getTotal($projectId) !== 10.250002) {
            $failures[] = 'Budget totals did not retain six-decimal precision.';
        }

        $series = $container['budget']->getDailyBudgetBreakdown($projectId);

        if (count($series) !== 1 || $series[0]['in'] !== 10.250002 || $series[0]['left'] !== 10.250002) {
            $failures[] = 'Same-day fractional budget adjustments were not accumulated correctly.';
        }

        list($validBudget, $budgetErrors) = $container['budget']->validateCreation(array(
            'project_id' => $projectId,
            'amount' => 'not-a-number',
            'date' => '2026-02-30',
        ));

        if ($validBudget || empty($budgetErrors['amount']) || empty($budgetErrors['date'])) {
            $failures[] = 'Invalid budget amount or date passed validation.';
        }

        $container['hourlyRate']->create($userId, '98.7654324', 'GBP', $today);

        if ($container['hourlyRate']->getCurrentRate($userId) !== 98.765432) {
            $failures[] = 'Hourly rates did not retain six-decimal precision.';
        }

        list($validRate, $rateErrors) = $container['hourlyRate']->validateCreation(array(
            'user_id' => $userId,
            'rate' => '-1',
            'date_effective' => '2026-02-30',
            'currency' => 'BAD',
        ));

        if ($validRate || empty($rateErrors['rate']) || empty($rateErrors['date_effective'])) {
            $failures[] = 'Invalid hourly rate or date passed validation.';
        }

        $container['currencyModel']->createWithoutComment('EUR', '1.2345678');

        if ((float) $container['currencyModel']->getByCurrency('EUR')['rate'] !== 1.234568) {
            $failures[] = 'Manual currency rates were not normalized to six decimal places.';
        }
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

echo 'CostControl financial model integration passed.' . PHP_EOL;
