<div class="cost-control-page-header relative">
    <div id="RateReminder" class="alert alert-error alert-fade-out" style="">
        <?= t('The next update for live rates will be on ') . $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) ?>
    </div>
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
                <th class="column-7"><?= t('Code') ?></th>
                <th class="column-35"><?= t('Currency') ?></th>
                <th class="column-8"><?= t('Manual Rate') ?></th>
                <th class="column-10"><?= t('Last Modified') ?></th>
                <th class=""><?= t('Comment') ?></th>
                <th class="column-8"><?= t('Live Rate') ?></th>
                <th class="column-10"><?= t('Live Rate Updated') ?></th>
            </tr>
            <?php foreach ($rates as $rate): ?>
            <tr class="">
                <td class="">
                    <span class="currency-coins-icon"></span> <strong><?= $this->text->e($rate['currency']) ?></strong>
                </td>
                <td class=""><?= $this->text->e($currencies[$rate['currency']]) ?></td>
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
        <form class="plugin-installed-filter">
            <label for="CurrencyCodeSearch">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-filter-square" viewBox="0 0 16 16">
                    <title><?= t('Filter Currency Codes') ?></title>
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                    <path d="M6 11.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </label>
            <input type="search" id="CurrencyCodeSearch" class="search-input" placeholder="<?= t('EUR') ?>" title="<?= t('Search Currency Code') ?>" autocomplete="off" autofocus="autofocus">
        </form>
        </table>
        <a href="https://www.exchangerate-api.com">Live Rates By Exchange Rate API</a>
        Last Checked: <?= $this->dt->datetime($this->model->configModel->get('last_checked_liverates', time() - 86401)) ?><br>
        Last Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_last_updated', time())) ?><br>
        Next Update to Live Rate Data: <?= $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time())) ?>
    <?php endif ?>

</div>
