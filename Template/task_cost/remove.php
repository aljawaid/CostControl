<div class="page-header">
    <h2><?= t('Remove Static Task Cost') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info"><?= t('This cost will be permanently removed.') ?></p>

    <?= $this->modal->confirmButtons(
        'TaskCostController',
        'remove',
        array('plugin' => 'CostControl', 'project_id' => $project['id'], 'task_id' => $task['id'])
    ) ?>
</div>
