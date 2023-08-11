<div class="page-header">
    <h2 class=""><?= t('Budget Overview') ?></h2>
</div>

<?php if (!empty($daily_budget)): ?>
    <div id="budget-chart" class="">
        <div id="chart" data-date-format="<?= e('%%Y-%%m-%%d') ?>" data-metrics='<?= json_encode($daily_budget, JSON_HEX_APOS) ?>' data-labels='<?= json_encode(array('in' => t('Budget Line'), 'out' => t('Expenses'), 'left' => t('Remaining'), 'value' => t('Amount'), 'date' => t('Date'), 'type' => t('Type')), JSON_HEX_APOS) ?>'></div>
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
