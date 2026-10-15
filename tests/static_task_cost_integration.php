<?php

/**
 * Exercise static task cost persistence, project isolation and status totals.
 *
 * Related files: Model/TaskCost.php and Controller/TaskCostController.php.
 */

if ($argc !== 2 || ! is_file($argv[1] . '/app/common.php')) {
    fwrite(STDERR, 'Usage: php tests/static_task_cost_integration.php /path/to/kanboard' . PHP_EOL);
    exit(2);
}

$kanboardRoot = realpath($argv[1]);
$databaseFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cost-control-task-cost-' . bin2hex(random_bytes(8)) . '.sqlite';
$failures = array();

define('PLUGINS_DIR', dirname(__DIR__, 2));
define('DB_FILENAME', $databaseFile);

chdir($kanboardRoot);
require $kanboardRoot . '/app/common.php';

$loadedPlugins = $container['pluginLoader']->getPlugins();

if (! isset($loadedPlugins['CostControl'])) {
    $failures[] = 'CostControl did not load in the disposable Kanboard instance.';
} else {
    $projectId = $container['projectModel']->create(array('name' => 'Static task cost test'));
    $otherProjectId = $container['projectModel']->create(array('name' => 'Static task cost isolation test'));
    $pendingTaskId = $container['taskCreationModel']->create(array(
        'project_id' => $projectId,
        'title' => 'Pending contractor cost',
    ));
    $confirmedTaskId = $container['taskCreationModel']->create(array(
        'project_id' => $projectId,
        'title' => 'Confirmed invoice cost',
    ));

    if (! $projectId || ! $otherProjectId || ! $pendingTaskId || ! $confirmedTaskId) {
        $failures[] = 'Unable to create disposable Kanboard records.';
    } else {
        list($validCost, $costErrors) = $container['taskCost']->validate(array('amount' => '125.1234564'));
        list($validNegativeCost, $negativeCostErrors) = $container['taskCost']->validate(array('amount' => '-1'));

        if (! $validCost || $costErrors !== array()) {
            $failures[] = 'A valid static task cost did not pass validation.';
        }

        if ($validNegativeCost || empty($negativeCostErrors['amount'])) {
            $failures[] = 'A negative static task cost passed validation.';
        }

        $container['taskCost']->save($pendingTaskId, $projectId, '125.1234564');
        $container['taskCost']->save($confirmedTaskId, $projectId, '75.5000004');
        $container['taskCost']->save($pendingTaskId, $projectId, '150.6543214');

        $storedCost = $container['taskCost']->getByTaskId($pendingTaskId, $projectId);
        $rowCount = $container['db']->table('cost_control_task_costs')->eq('task_id', $pendingTaskId)->count();

        if (number_format((float) $storedCost['amount'], 6, '.', '') !== '150.654321' || $rowCount !== 1) {
            $failures[] = 'Static task cost upsert or fixed-scale normalisation failed.';
        }

        if ($container['taskCost']->save($pendingTaskId, $otherProjectId, '999') !== false) {
            $failures[] = 'A static task cost crossed the route project boundary.';
        }

        $summary = $container['taskCost']->getProjectSummary($projectId);

        if ($summary['pending'] !== 226.154321 || $summary['confirmed'] !== 0.0) {
            $failures[] = 'Open task costs were not classified as pending.';
        }

        $container['taskStatusModel']->close($confirmedTaskId);
        $summary = $container['taskCost']->getProjectSummary($projectId);

        if ($summary['pending'] !== 150.654321 || $summary['confirmed'] !== 75.5) {
            $failures[] = 'Closed task costs were not classified as confirmed.';
        }

        $container['taskStatusModel']->open($confirmedTaskId);
        $summary = $container['taskCost']->getProjectSummary($projectId);

        if ($summary['pending'] !== 226.154321 || $summary['confirmed'] !== 0.0) {
            $failures[] = 'Reopened task costs did not return to pending.';
        }

        $container['db']->table('tasks')->eq('id', $confirmedTaskId)->remove();

        if ($container['db']->table('cost_control_task_costs')->eq('task_id', $confirmedTaskId)->count() !== 0) {
            $failures[] = 'Static task cost did not cascade when its task was removed.';
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

echo 'CostControl static task cost integration passed.' . PHP_EOL;
