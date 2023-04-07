<div class="alert alert-error alert-fade-out" style="top: 50%; bottom: initial;"><?= t('The next update for live rates will be on ') . $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) ?></div>
<div class="cost-control-page-header">
    <h2 class="">
        <span class="currency-wallet-icon"></span> <?= t('Exchange Rates') ?>
    </h2>
    <ul class="currency-actions">
        <li class="">
            <?= $this->modal->medium('plus', t('Add or change currency rate'), 'CostController', 'create', array('plugin' => 'CostControl')) ?>
        </li>
        <?php if ($this->user->hasAccess('ConfigController', 'application')): ?>
            <li class="">
                <?= $this->modal->medium('edit', t('Change base currency'), 'CostController', 'change', array('plugin' => 'CostControl')) ?>
            </li>
            <li class="">
                <?= $this->url->link(t('Edit Reference Currency'), 'ConfigController', 'application', array(), false, '', t('Settings'), false, 'CostControlSettings') ?>
            </li>
        <?php endif ?>
    </ul>

    <fieldset class="">
        <legend><?= t('Application Currencies') ?></legend>
        <div class="base-currency-wrapper">
            <div class="base-currency">
                <h4 class="base-currency-title"><?= t('Base Currency') ?></h4> <span class="base-currency-code"><?= $application_currency ?></span>
            </div>
        </div>
        <?php if (!empty($this->model->configModel->get('cost_control_reference_currency', ''))): ?>
            <div class="reference-currency-wrapper">
                <div class="reference-currency">
                    <h4 class="reference-currency-title"><?= t('Reference Currency') ?></h4>
                    <span class="reference-currency-code">
                        <?= $this->model->configModel->get('cost_control_reference_currency', '') ?>
                    </span>
                    <span class="reference-currency-manual-rate" title="<?= t('Last Modified: ') . $this->dt->datetime($this->model->currencyModel->getReferenceCurrency()['last_modified']) ?>">
                        <?= t('Manual Rate')?> <?= (n($this->model->currencyModel->getReferenceCurrency()['rate']) > 0) ? n($this->model->currencyModel->getReferenceCurrency()['rate']) : t('Not set') ?>
                    </span>
                    <span class="reference-currency-live-rate" title="<?= t('Last Updated: ') . $this->dt->datetime($this->model->currencyModel->getReferenceCurrency()['live_rate_updated']) ?>">
                        <?= t('Live Rate') . n($this->model->currencyModel->getReferenceCurrency()['live_rate']) ?>
                    </span>
                </div>
            </div>
        <?php endif ?>
    </fieldset>

    <?php if (! empty($rates)): ?>
        <table class="table-striped">
            <tr class="">
                <th class="column-35"><?= t('Currency') ?></th>
                <th class=""><?= t('Manual Rate') ?></th>
                <th class=""><?= t('Last Modified') ?></th>
                <th class=""><?= t('Comment') ?></th>
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
                <td class="manual-rate-comment"><?= ($rate['comment']) ?></td>
                <td class="live-rate">
                    <?= ($rate['live_rate'] > 0) ? n($rate['live_rate']) : '' ?>
                </td>
                <td class="live-rate-last-updated"><?= ($rate['live_rate_updated'] > 0) ? $this->dt->datetime($rate['live_rate_updated']) : '' ?></td>
            </tr>
            <?php endforeach ?>
        </table>
        <a href="https://www.exchangerate-api.com">Live Rates By Exchange Rate API</a>
        Last Checked: <?= $this->dt->datetime($this->model->configModel->get('last_checked_liverates', time() - 86401)) ?><br>
        Last Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_last_updated', time())) ?><br>
        Next Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) ?>
    <?php endif ?>

</div>
