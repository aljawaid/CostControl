<?php

/**
 * Verify CostControl authorization, project ownership and output-safety boundaries.
 *
 * Related files: Plugin.php, Controller/CostController.php,
 * Controller/BudgetLineController.php, Model/Budget.php,
 * Model/ExtendedCurrencyModel.php, Template/currency/show.php,
 * Template/cost-control/edit_comment.php and Template/config/settings.php.
 */

$pluginRoot = dirname(__DIR__);
$plugin = file_get_contents($pluginRoot . '/Plugin.php');
$costController = file_get_contents($pluginRoot . '/Controller/CostController.php');
$budgetController = file_get_contents($pluginRoot . '/Controller/BudgetLineController.php');
$budgetModel = file_get_contents($pluginRoot . '/Model/Budget.php');
$currencyModel = file_get_contents($pluginRoot . '/Model/ExtendedCurrencyModel.php');
$currencyList = file_get_contents($pluginRoot . '/Template/currency/show.php');
$commentForm = file_get_contents($pluginRoot . '/Template/cost-control/edit_comment.php');
$configSettings = file_get_contents($pluginRoot . '/Template/config/settings.php');
$failures = array();

foreach (
    array(
    "applicationAccessMap->add('CostController', '*', Role::APP_ADMIN)",
    "applicationAccessMap->add('CostController', array('showEveryone', 'create', 'save', 'saveWithoutComment', 'editComment', 'saveCommentForm'), Role::APP_USER)",
    "projectAccessMap->add('BudgetLineController', '*', Role::PROJECT_MANAGER)",
    "addRoute('/cost-control/currencies/:currency', 'CostController', 'editComment', 'CostControl')",
    ) as $snippet
) {
    if (strpos($plugin, $snippet) === false) {
        $failures[] = 'Missing access or route contract: ' . $snippet;
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
