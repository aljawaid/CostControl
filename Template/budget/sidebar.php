<div class="sidebar">
    <ul>
        <li <?= $this->app->checkMenuSelection('BudgetController', 'show') ?>>
            <?= $this->url->link(t('Budget overview'), 'BudgetController', 'show', array('plugin' => 'CostControl', 'project_id' => $project['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('BudgetLineController', 'show') ?>>
            <?= $this->url->link(t('Budget lines'), 'BudgetLineController', 'show', array('plugin' => 'CostControl', 'project_id' => $project['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('BudgetController', 'breakdown') ?>>
            <?= $this->url->link(t('Cost breakdown'), 'BudgetController', 'breakdown', array('plugin' => 'CostControl', 'project_id' => $project['id'])) ?>
        </li>
    </ul>
</div>
