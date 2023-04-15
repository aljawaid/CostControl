<?php if ($this->user->hasProjectAccess('BudgetController', 'index', $project['id'])): ?>
    <li class="">
        <?= $this->url->icon('pie-chart', t('Budget'), 'BudgetController', 'show', array('plugin' => 'CostControl', 'project_id' => $project['id'])) ?>
    </li>
<?php endif ?>
