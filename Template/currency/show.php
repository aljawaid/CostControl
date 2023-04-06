<?php
// 24 RATE LIMIT CHECK
if (time() - $this->model->configModel->get('cost_control_last_checked_live_rates', time() - 86401) > 86400) {
    // SAVE DATE IF GREATER THAN 24HRS
    $this->model->configModel->save(['cost_control_last_checked_live_rates'=> time()]);
    // CHECK JSON API
    $this->model->currencyModel->getLiveRates();
} 
?>
<div class="page-header">
    <h2 class="">
        <span class="currency-wallet-icon"></span> <?= t('Test Rates') ?>
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
                <th class=""><?= t('Manual Rate') ?></th>
                <th class=""> Last Modified</th>
                <th class="">Live Daily Rate</th>
                <th class=""> Live Rate Updated</th>
            </tr>
            <?php foreach ($rates as $rate): ?>
            <tr class="">
                <td class="">
                    <span class="currency-coins-icon"></span> <strong><?= $this->text->e($rate['currency']) ?></strong>
                </td>
                <td class="">
                    <?= ($rate['rate'] > 0) ? n($rate['rate']) : '' ?>
                </td>
                <td class="manual-rate-last-modified"><?= ($rate['last_modified'] > 0) ? $this->dt->datetime($rate['last_modified']) : '' ?></td>
                <td class="live-rate">
                    <?= ($rate['live_rate'] > 0) ? n($rate['live_rate']) : '' ?>
                </td>
                <td class="live-rate-last-updated"><?= ($rate['live_rate_updated'] > 0) ? $this->dt->datetime($rate['live_rate_updated']) : '' ?></td>
            </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <a href="https://www.exchangerate-api.com">Rates By Exchange Rate API</a>
    Last Checked: <?= $this->dt->datetime($this->model->configModel->get('last_checked_liverates', time() - 86401)) ?><br>
    Last Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_last_updated', time())) ?><br>
    Next Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) ?>
</div>
