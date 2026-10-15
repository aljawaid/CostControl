<div class="page-header">
    <h2 class=""><?= t('Budget Overview') ?></h2>
</div>

<?php if (!empty($daily_budget)): ?>
    <div id="budget-chart" class="">
        <div id="chart" data-date-format="<?= e('%%Y-%%m-%%d') ?>" data-metrics='<?= json_encode($daily_budget, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>' data-labels='<?= json_encode(array('in' => t('Budget Line'), 'out' => t('Expenses'), 'left' => t('Remaining'), 'value' => t('Amount'), 'date' => t('Date'), 'type' => t('Type')), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'></div>
    </div>
    <hr/>
    <table class="table-fixed table-stripped">
        <tr class="">
            <th class=""><?= t('Date') ?></th>
            <th class=""><?= t('Budget Line') ?></th>
            <th class=""><?= t('Expenses') ?></th>
            <th class=""><?= t('Remaining') ?></th>
        </tr>
        <?php foreach ($daily_budget as $line): ?>
            <tr class="">
                <td class=""><?= $this->dt->date($line['date']) ?></td>
                <td class=""><?= n($line['in']) ?></td>
                <td class=""><?= n($line['out']) ?></td>
                <td class=""><?= n($line['left']) ?></td>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p class="alert"><?= t('There is not enough data to show something.') ?></p>
<?php endif ?>

<?= $this->asset->js('plugins/CostControl/Assets/js/BudgetChart.js') ?>

<hr/>
<div class="page-header task-cost-summary-heading">
    <h2>
        <?= t('Static Task Cost Overview') ?>
        <?= $this->app->tooltipMarkdown(t('Static costs use the application base currency. Active tasks are pending and closed tasks are confirmed. Hourly subtask labour remains in the budget chart above.')) ?>
    </h2>
</div>

<div id="task-cost-summary-chart" data-metrics='<?= json_encode($task_cost_summary, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>' data-labels='<?= json_encode(array('budget' => t('Budget'), 'pending' => t('Pending'), 'confirmed' => t('Confirmed')), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'></div>

<table class="table-fixed table-stripped task-cost-summary-table">
    <thead>
        <tr>
            <th><?= t('Budget') ?></th>
            <th><?= t('Pending') ?></th>
            <th><?= t('Confirmed') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= n($task_cost_summary['budget']) ?></td>
            <td><?= n($task_cost_summary['pending']) ?></td>
            <td><?= n($task_cost_summary['confirmed']) ?></td>
        </tr>
    </tbody>
</table>
