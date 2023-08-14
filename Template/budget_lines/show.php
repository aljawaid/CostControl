<div class="page-header">
    <h2 class=""><?= t('Budget lines') ?></h2>
    <ul class="">
        <li class="">
            <?= $this->modal->medium('plus', t('New Budget Line'), 'BudgetLineController', 'create', array('plugin' => 'CostControl', 'project_id' => $project['id'])) ?>
        </li>
    </ul>
</div>
<?php if (!empty($lines)): ?>
    <table class="table-fixed table-striped">
        <tr class="">
            <th class="column-20"><?= t('Budget Line') ?></th>
            <th class="column-20"><?= t('Date') ?></th>
            <th class=""><?= t('Comment') ?></th>
            <th class=""><?= t('Action') ?></th>
        </tr>
        <?php foreach ($lines as $line): ?>
            <tr class="">
                <td class=""><?= n($line['amount']) ?></td>
                <td class=""><?= $this->dt->date($line['date']) ?></td>
                <td class=""><?= $this->helper->text->e($line['comment']) ?></td>
                <td class="">
                    <?= $this->modal->confirm('trash-o', t('Remove'), 'BudgetLineController', 'confirm', array('plugin' => 'CostControl', 'project_id' => $project['id'], 'budget_id' => $line['id'])) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p class="alert alert-info"><?= t('There is no budget line.') ?></p>
<?php endif ?>
