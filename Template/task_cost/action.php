<?php if ($this->user->hasProjectAccess('TaskCostController', 'edit', $task['project_id'])): ?>
    <li>
        <?= $this->modal->medium('money', t('Set static cost'), 'TaskCostController', 'edit', array('plugin' => 'CostControl', 'project_id' => $task['project_id'], 'task_id' => $task['id'])) ?>
    </li>
<?php endif ?>
