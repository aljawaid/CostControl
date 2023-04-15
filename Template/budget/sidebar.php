<div class="sidebar budget-sidebar">
    <ul class="">
        <li <?= $this->app->checkMenuSelection('BudgetController', 'show') ?>>
            <?= $this->url->link(t('Budget Overview'), 'BudgetController', 'show', array('plugin' => 'CostControl', 'project_id' => $project['id']), false, 'cost-control-menu-item') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('BudgetLineController', 'show') ?>>
            <?= $this->url->link(t('Budget Lines'), 'BudgetLineController', 'show', array('plugin' => 'CostControl', 'project_id' => $project['id']), false, 'cost-control-menu-item') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('BudgetController', 'breakdown') ?>>
            <?= $this->url->link(t('Cost Breakdown'), 'BudgetController', 'breakdown', array('plugin' => 'CostControl', 'project_id' => $project['id']), false, 'cost-control-menu-item') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('CostController') ?>>
            <?= $this->url->link(t('Cost Control'), 'CostController', 'showEveryone', array('plugin' => 'CostControl'), false, 'cost-control-menu-item') ?>
        </li>
    </ul>
</div>
