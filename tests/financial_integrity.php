<?php

/**
 * Verify CostControl financial precision, validation and ownership contracts.
 *
 * Related files: Model/Budget.php, Model/HourlyRate.php,
 * Model/ExtendedCurrencyModel.php, Controller/CostController.php,
 * Controller/HourlyRateController.php and Schema/*.php.
 */

$pluginRoot = dirname(__DIR__);
$budget = file_get_contents($pluginRoot . '/Model/Budget.php');
$hourlyRate = file_get_contents($pluginRoot . '/Model/HourlyRate.php');
$currency = file_get_contents($pluginRoot . '/Model/ExtendedCurrencyModel.php');
$costController = file_get_contents($pluginRoot . '/Controller/CostController.php');
$hourlyController = file_get_contents($pluginRoot . '/Controller/HourlyRateController.php');
$sqliteSchema = file_get_contents($pluginRoot . '/Schema/Sqlite.php');
$mysqlSchema = file_get_contents($pluginRoot . '/Schema/Mysql.php');
$postgresSchema = file_get_contents($pluginRoot . '/Schema/Postgres.php');
$failures = array();

foreach (
    array(
        'round((float) $result[\'total\'], 6)',
        'Multiple adjustments on one day must be accumulated',
        "new Validators\\Numeric('amount'",
        "new Validators\\Date('date'",
        'normalizeFinancialValue($amount)',
        'round((float) $hourly_price * (float) $record[\'time_spent\'], 6)',
    ) as $snippet
) {
    if (strpos($budget, $snippet) === false) {
        $failures[] = 'Missing budget integrity contract: ' . $snippet;
    }
}

if (strpos($budget, '(int) $in[$date]') !== false || strpos($budget, '(int) $out[$date]') !== false) {
    $failures[] = 'Budget chart values are still truncated to integers.';
}

foreach (
    array(
        "new Validators\\Range('rate'",
        "new Validators\\Date('date_effective'",
        "errors['currency'][] = t('Invalid currency')",
        'normalizeFinancialValue($rate)',
        'getById($rate_id, $user_id)',
        "eq('user_id', \$user_id)->remove()",
    ) as $snippet
) {
    if (strpos($hourlyRate, $snippet) === false) {
        $failures[] = 'Missing hourly-rate integrity contract: ' . $snippet;
    }
}

foreach (
    array(
        "values['user_id'] = \$user['id']",
        "getById(\$rate_id, \$user['id'])",
        "remove(\$this->request->getIntegerParam('rate_id'), \$user['id'])",
        "flash->failure(t('Unable to remove this rate.'))",
    ) as $snippet
) {
    if (strpos($hourlyController, $snippet) === false) {
        $failures[] = 'Missing route-user financial boundary: ' . $snippet;
    }
}

foreach (
    array(
        'normalizeFinancialValue($live_rate)',
        'normalizeFinancialValue($rate)',
        'MAX_FINANCIAL_VALUE',
        'round((float) $rate * (float) $price, 6)',
    ) as $snippet
) {
    if (strpos($currency, $snippet) === false) {
        $failures[] = 'Missing currency precision contract: ' . $snippet;
    }
}

if (strpos($costController, 'isSupportedRate($values[\'rate\'])') === false) {
    $failures[] = 'Manual currency rates are not constrained to the supported financial range.';
}

foreach (array($sqliteSchema, $mysqlSchema, $postgresSchema) as $schema) {
    foreach (
        array(
            'cost_control_budget_project_date_idx',
            'cost_control_hourly_user_date_idx',
            'Related test: tests/database_migrations.php',
        ) as $snippet
    ) {
        if (strpos($schema, $snippet) === false) {
            $failures[] = 'Missing cross-database migration contract: ' . $snippet;
        }
    }
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl financial integrity contract passed.' . PHP_EOL;
