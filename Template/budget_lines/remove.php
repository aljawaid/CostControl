<div class="page-header">
    <h2 class=""><?= t('Remove Budget Line') ?></h2>
</div>
<div class="confirm">
    <p class="alert alert-info"><?= t('Do you really want to remove this budget line?') ?></p>
    <?= $this->modal->confirmButtons('BudgetLineController', 'remove', array('plugin' => 'CostControl', 'project_id' => $project['id'], 'budget_id' => $budget_id)) ?>
</div>
