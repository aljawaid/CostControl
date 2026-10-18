<?php
/**
 * Render a manager-only static task cost in task details.
 *
 * Related tests: tests/security_contract.php and tests/visual_contract.php.
 */
?>
<?php if ($this->user->hasProjectAccess('TaskCostController', 'edit', $task['project_id'])): ?>
    <?php $taskCost = $this->model->taskCost->getByTaskId($task['id'], $task['project_id']) ?>
    <?php if (! empty($taskCost)): ?>
        <li>
            <strong><?= t('Static cost:') ?></strong>
            <span><?= n($taskCost['amount']) ?> <?= $this->text->e($this->model->configModel->get('application_currency')) ?></span>
            <span class="task-cost-status task-cost-status-<?= (int) $task['is_active'] === 1 ? 'pending' : 'confirmed' ?>">
                <?= (int) $task['is_active'] === 1 ? t('Pending') : t('Confirmed') ?>
            </span>
        </li>
    <?php endif ?>
<?php endif ?>
