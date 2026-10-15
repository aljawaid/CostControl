<?php

/**
 * Verify CostControl authorization, project ownership and output-safety boundaries.
 *
 * Related files: Plugin.php, Controller/CostController.php,
 * Controller/BudgetLineController.php, Controller/TaskCostController.php,
 * Model/Budget.php, Model/TaskCost.php,
 * Model/ExtendedCurrencyModel.php, Template/currency/show.php,
 * Template/cost-control/edit_comment.php and Template/config/settings.php.
 */

$pluginRoot = dirname(__DIR__);
$plugin = file_get_contents($pluginRoot . '/Plugin.php');
$costController = file_get_contents($pluginRoot . '/Controller/CostController.php');
$budgetController = file_get_contents($pluginRoot . '/Controller/BudgetLineController.php');
$taskCostController = file_get_contents($pluginRoot . '/Controller/TaskCostController.php');
$budgetModel = file_get_contents($pluginRoot . '/Model/Budget.php');
$taskCostModel = file_get_contents($pluginRoot . '/Model/TaskCost.php');
$currencyModel = file_get_contents($pluginRoot . '/Model/ExtendedCurrencyModel.php');
$currencyList = file_get_contents($pluginRoot . '/Template/currency/show.php');
$commentForm = file_get_contents($pluginRoot . '/Template/cost-control/edit_comment.php');
$configSettings = file_get_contents($pluginRoot . '/Template/config/settings.php');
$taskCostTemplates = array(
    file_get_contents($pluginRoot . '/Template/task_cost/action.php'),
    file_get_contents($pluginRoot . '/Template/task_cost/details.php'),
);
$failures = array();

foreach (
    array(
    "applicationAccessMap->add('CostController', '*', Role::APP_ADMIN)",
    "applicationAccessMap->add('CostController', array('showEveryone', 'create', 'save', 'saveWithoutComment', 'editComment', 'saveCommentForm'), Role::APP_USER)",
    "projectAccessMap->add('BudgetLineController', '*', Role::PROJECT_MANAGER)",
    "projectAccessMap->add('TaskCostController', '*', Role::PROJECT_MANAGER)",
    "addRoute('/cost-control/currencies/:currency', 'CostController', 'editComment', 'CostControl')",
    ) as $snippet
) {
    if (strpos($plugin, $snippet) === false) {
        $failures[] = 'Missing access or route contract: ' . $snippet;
    }
}

foreach ($taskCostTemplates as $taskCostTemplate) {
    if (strpos($taskCostTemplate, "hasProjectAccess('TaskCostController', 'edit', \$task['project_id'])") === false) {
        $failures[] = 'Static task cost UI is visible outside the project-manager access boundary.';
    }
}

foreach (
    array(
        'public function save()',
        'public function confirm()',
        'public function remove()',
        '$this->checkCSRFParam();',
        "getByTaskId(\$task['id'], \$project['id'])",
        "save(\$task['id'], \$project['id'], \$values['amount'])",
        "remove(\$task['id'], \$project['id'])",
    ) as $snippet
) {
    if (strpos($taskCostController, $snippet) === false) {
        $failures[] = 'Missing static task cost controller boundary: ' . $snippet;
    }
}

foreach (
    array(
        "->eq(TaskModel::TABLE . '.project_id', \$projectId)",
        "(int) \$task['project_id'] !== (int) \$projectId",
        "new Validators\\Range('amount'",
    ) as $snippet
) {
    if (strpos($taskCostModel, $snippet) === false) {
        $failures[] = 'Missing static task cost model boundary: ' . $snippet;
    }
}

foreach (
    array(
    "configModel->save(array('application_currency' => \$currency))",
    "array_key_exists(\$currency, \$this->currencyModel->getCurrencies())",
    "getByCurrency(\$currency)",
    "is_string(\$values['comment'])",
    "editComment(\$values['currency'], \$values['comment'])",
    ) as $snippet
) {
    if (strpos($costController, $snippet) === false) {
        $failures[] = 'Missing currency write boundary: ' . $snippet;
    }
}

if (strpos($costController, 'configModel->save($values)') !== false) {
    $failures[] = 'Arbitrary configuration keys can still reach ConfigModel.';
}

foreach (
    array(
    "\$values['project_id'] = \$project['id'];",
    "getById(\$budget_id, \$project['id'])",
    "remove(\$this->request->getIntegerParam('budget_id'), \$project['id'])",
    ) as $snippet
) {
    if (strpos($budgetController, $snippet) === false) {
        $failures[] = 'Missing route-project controller binding: ' . $snippet;
    }
}

foreach (
    array(
    "->eq('id', \$budget_id)",
    "->eq('project_id', \$project_id)",
    'public function remove($budget_id, $project_id)',
    ) as $snippet
) {
    if (strpos($budgetModel, $snippet) === false) {
        $failures[] = 'Missing budget model ownership boundary: ' . $snippet;
    }
}

foreach (
    array(
    'public function getByCurrency($currency)',
    'public function editComment($currency, $comment)',
    "->eq('currency', \$currency)->update(array('comment' => \$comment))",
    ) as $snippet
) {
    if (strpos($currencyModel, $snippet) === false) {
        $failures[] = 'Missing existing-currency update boundary: ' . $snippet;
    }
}

if (strpos($currencyList, "text->e(\$rate['comment'])") === false) {
    $failures[] = 'Persisted currency comments are not HTML-escaped.';
}

if (strpos($currencyList, "'comment' => \$rate['comment']") !== false) {
    $failures[] = 'Persisted comments are still disclosed in generated URLs.';
}

foreach (
    array(
    "form->hidden('currency', \$values)",
    "form->text('comment', \$values",
    "text->e(\$values['currency'])",
    ) as $snippet
) {
    if (strpos($commentForm, $snippet) === false) {
        $failures[] = 'Missing safe comment form rendering: ' . $snippet;
    }
}

if (
    strpos($configSettings, "array('application_currency' => \$this->model->configModel->get('application_currency'))") === false
    || strpos($configSettings, "array('value=\"") !== false
) {
    $failures[] = 'The stored base currency bypasses the form helper escaping path.';
}

if ($failures !== array()) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

echo 'CostControl security contract passed.' . PHP_EOL;
