<div class="page-header">
    <h2 class=""><?= t('Currency rates') ?></h2>
    <ul class="">
        <li class="">
            <?= $this->modal->medium('plus', t('Add or change currency rate'), 'CurrencyController', 'create') ?>
        </li>
        <li class="">
            <?= $this->modal->medium('edit', t('Change reference currency'), 'CurrencyController', 'change') ?>
        </li>
    </ul>
</div>

<div class="panel">
    <strong><?= t('Reference currency: %s', $application_currency) ?></strong>
</div>

<?php if (! empty($rates)): ?>
    <table class="table-striped">
        <tr class="">
            <th class="column-35"><?= t('Currency') ?></th>
            <th class=""><?= t('Rate') ?></th>
            <th class="">Current Live Rate</th>
        </tr>
        <?php foreach ($rates as $rate): ?>
        <tr class="">
            <td class="">
                <strong><?= $this->text->e($rate['currency']) ?></strong>
            </td>
            <td class="">
                <?= n($rate['rate']) ?>
            </td>
            <td class="live-rate">
                <?= $this->helper->liveRateHelper->getLiveRate($application_currency, $rate_currency = $this->text->e($rate['currency'])) ?>
            </td>
        </tr>
        <?php endforeach ?>
    </table>
<?php endif ?>

<a href="https://www.exchangerate-api.com">Rates By Exchange Rate API</a>
