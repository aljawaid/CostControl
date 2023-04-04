<div class="page-header">
    <h2 class="">
        <span class="currency-wallet-icon"></span> <?= t('Currency Rates') ?>
    </h2>
    <ul class="">
        <li class="">
            <?= $this->modal->medium('plus', t('Add or change currency rate'), 'CurrencyController', 'create') ?>
        </li>
        <li class="">
            <?= $this->modal->medium('edit', t('Change reference currency'), 'CurrencyController', 'change') ?>
        </li>
    </ul>

    <div class="panel">
        <strong><?= t('Base Currency: %s', $application_currency) ?></strong>
    </div>

    <?php if (! empty($rates)): ?>
        <table class="table-striped">
            <tr class="">
                <th class="column-35"><?= t('Currency') ?></th>
                <th class=""><?= t('Rate') ?></th>
                <th class=""> Last Modified</th>
                <th class="">Live Daily Rate</th>
            </tr>
            <?php foreach ($rates as $rate): ?>
            <tr class="">
                <td class="">
                    <span class="currency-coins-icon"></span> <strong><?= $this->text->e($rate['currency']) ?></strong>
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

    <a href="https://www.exchangerate-api.com">Rates By Exchange Rate API</a> Last Updated: date / Next Update: date
<?= $this->helper->liveRateHelper->getLiveRate($last_updated) ?>
</div>
