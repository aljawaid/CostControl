<div class="page-header">
    <h2 class=""><?= t('Cost Breakdown') ?></h2>
</div>

<?php if ($paginator->isEmpty()): ?>
    <p class="alert"><?= t('There is nothing to show.') ?></p>
<?php else: ?>
    <table class="table-fixed">
        <tr class="">
            <th class="column-20"><?= $paginator->order(t('Task'), 'task_title') ?></th>
            <th class="column-25"><?= $paginator->order(t('Subtask'), 'subtask_title') ?></th>
            <th class="column-20"><?= $paginator->order(t('User'), 'username') ?></th>
            <th class="column-10"><?= t('Cost') ?></th>
            <th class="column-10"><?= $paginator->order(t('Time Spent'), \Kanboard\Model\SubtaskTimeTrackingModel::TABLE . '.time_spent') ?></th>
            <th class="column-15"><?= $paginator->order(t('Date'), 'start') ?></th>
        </tr>
        <?php foreach ($paginator->getCollection() as $record): ?>
            <tr class="">
                <td class="">
                    <?= $this->url->link($this->helper->text->e($record['task_title']), 'TaskViewController', 'show', array('project_id' => $project['id'], 'task_id' => $record['task_id'])) ?>
                </td>
                <td class="">
                    <?= $this->url->link($this->helper->text->e($record['subtask_title']), 'TaskViewController', 'show', array('project_id' => $project['id'], 'task_id' => $record['task_id'])) ?>
                </td>
                <td class=""><?= $this->url->link($this->helper->text->e($record['name'] ?: $record['username']), 'UserViewController', 'show', array('user_id' => $record['user_id'])) ?>
                </td>
                <td class=""><?= n($record['cost']) ?></td>
                <td class=""><?= n($record['time_spent']) . ' ' . t('hours') ?></td>
                <td class=""><?= $this->dt->date($record['start']) ?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <?= $paginator ?>
<?php endif ?>
