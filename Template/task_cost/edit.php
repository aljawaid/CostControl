<div class="page-header">
    <h2><?= t('Static Task Cost') ?></h2>
</div>

<p class="form-help">
    <?= t('The cost uses the base currency %s.', $this->text->e($application_currency)) ?>
    <?= $this->app->tooltipMarkdown(t('An active task is pending. A closed task is confirmed, regardless of its column name. Configure Kanboard to close tasks automatically in a completion column if required. Hourly subtask labour remains separate.')) ?>
</p>

<form method="post" action="<?= $this->url->href('TaskCostController', 'save', array('plugin' => 'CostControl', 'project_id' => $project['id'], 'task_id' => $task['id'])) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Amount'), 'amount') ?>
    <?= $this->form->text('amount', $values, $errors, array('required', 'autofocus'), 'form-numeric') ?>

    <?= $this->modal->submitButtons() ?>
</form>

<?php if (! empty($task_cost)): ?>
    <p>
        <?= $this->modal->confirm('trash-o', t('Remove static cost'), 'TaskCostController', 'confirm', array('plugin' => 'CostControl', 'project_id' => $project['id'], 'task_id' => $task['id'])) ?>
    </p>
<?php endif ?>
