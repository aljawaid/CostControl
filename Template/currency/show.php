<?php
// CHECK FOR LIVE RATES ONE MIN AFTER NEXT SCHEDULED UPDATE FROM THE JSON
if (time() - $this->model->configModel->get('cost_control_next_update', time() - 61) > 60) {
    // SAVE DATE WHEN CHECKED
    $this->model->configModel->save(['cost_control_last_checked_live_rates'=> time()]);
    // CHECK JSON API
    $this->model->currencyModel->getLiveRates();
} else {
    echo '<div class="alert alert-error alert-fade-out" style="top: 50%; bottom: initial;">'. t('The next update for live rates will be on ') . $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) .'</div>';
}
?>
<div class="cost-control-page-header">
    <h2 class="">
        <span class="currency-wallet-icon"></span> <?= t('Currency Rates') ?>
    </h2>
    <ul class="currency-actions">
        <li class="">
            <?= $this->modal->medium('plus', t('Add or change currency rate'), 'CurrencyController', 'create') ?>
        </li>
        <li class="">
            <?= $this->modal->medium('edit', t('Change base currency'), 'CurrencyController', 'change') ?>
        </li>
    </ul>

    <div class="panel">
        <strong><?= t('Base Currency: %s', $application_currency) ?></strong>
        <span class=""><?= t('Reference Currency') ?> <?= $this->model->configModel->get('cost_control_reference_currency', '') ?></span>
    </div>

    <?php if (! empty($rates)): ?>
        <table class="table-striped">
            <tr class="">
                <th class="column-35"><?= t('Currency') ?></th>
                <th class=""><?= t('Manual Rate') ?></th>
                <th class=""><?= t('Last Modified') ?></th>
                <th class=""><?= t('Live Rate') ?></th>
                <th class=""><?= t('Live Rate Updated') ?></th>
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

    <a href="https://www.exchangerate-api.com">Live Rates By Exchange Rate API</a>
    Last Checked: <?= $this->dt->datetime($this->model->configModel->get('last_checked_liverates', time() - 86401)) ?><br>
    Last Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_last_updated', time())) ?><br>
    Next Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) ?>
</div>
