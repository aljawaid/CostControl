<div class="cost-control-page-header relative">
    <div id="RateReminder" class="alert alert-error alert-fade-out" style="">
        <?= t('The next update for live rates will be on %s', $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time()))) ?>
    </div>
    <h2 class="">
        <span class="currency-wallet-icon"></span> <?= t('Exchange Rates') ?>
    </h2>
    <ul class="currency-actions">
        <li class="update-manual-rate">
            <?= $this->modal->medium('plus', t('Update Manual Rate'), 'CostController', 'create', array('plugin' => 'CostControl')) ?>
        </li>
        <?php if ($this->user->hasAccess('ConfigController', 'application')): ?>
            <li class="change-base-currency">
                <?= $this->modal->medium('edit', t('Change Base Currency'), 'CostController', 'change', array('plugin' => 'CostControl')) ?>
            </li>
            <li class="">
                <span class="reference-currency-icon"></span> <?= $this->url->link(t('Change Reference Currency'), 'ConfigController', 'application', array(), false, '', t('Go to Settings'), false, 'CostControlSettings') ?>
            </li>
        <?php endif ?>
    </ul>
    <fieldset class="app-currencies">
        <legend><?= t('Application Currencies') ?></legend>
        <div class="app-currency-wrapper">
            <div class="app-currency-list">
                <div class="base-currency-wrapper">
                    <span class="base-currency-title"><?= t('Base Currency') ?></span>
                    <span class="base-currency-code"><?= $application_currency ?></span>
                    <span class="base-currency-value"><span class="base-rate-home-icon"></span> 1.00</span>
                </div>
                <div class="reference-currency-wrapper">
                    <span class="reference-currency-title"><?= t('Reference Currency') ?></span>
                    <?php if (!empty($this->model->configModel->get('cost_control_reference_currency', ''))): ?>
                        <span class="reference-currency-code">
                            <?= $this->model->configModel->get('cost_control_reference_currency', '') ?>
                        </span>
                        <span class="manual-live-wrapper">
                            <span class="reference-currency-manual-rate" title="<?= t('Manual Rate Last Modified: %s', $this->dt->datetime($this->model->currencyModel->getReferenceCurrency()['last_modified'])) ?>">
                                <?= (n($this->model->currencyModel->getReferenceCurrency()['rate']) > 0) ? '<span class="manual-rate-icon"></span> ' . n($this->model->currencyModel->getReferenceCurrency()['rate']) : '' ?>
                            </span>
                            <?= (n($this->model->currencyModel->getReferenceCurrency()['rate']) > 0) ? '<span class="spacer"></span>' : '' ?>
                            <span class="reference-currency-live-rate" title="<?= t('Live Rate Last Updated: %s', $this->dt->datetime($this->model->currencyModel->getReferenceCurrency()['live_rate_updated'])) ?>">
                                <span class="live-rate-icon"></span> <?= n($this->model->currencyModel->getReferenceCurrency()['live_rate']) ?>
                            </span>
                        </span>
                    <?php else: ?>
                        <?php if ($this->user->hasAccess('ConfigController', 'application')): ?>
                            <span class="add-reference-currency">
                                <span class="reference-currency-icon"></span>
                                <span class="add-reference-currency-link">
                                    <i class="fa fa-plus"></i> <?= $this->url->link(t('Add'), 'ConfigController', 'application', array(), false, '', t('Go to Settings'), false, 'CostControlSettings') ?>
                                </span>
                            </span>
                        <?php else: ?>
                            <span class="add-reference-currency">
                                <span class="reference-currency-icon"></span>
                                <span class="add-reference-currency-link">
                                    <i class="not-set pp-grey"><?= t('Not set') ?></i>
                                </span>
                            </span>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </fieldset>

    <?php if (!empty($rates)): ?>
        <form class="currency-filter">
            <label for="CurrencyCodeSearch">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-filter-square" viewBox="0 0 16 16">
                    <title><?= t('Filter Currency Codes') ?></title>
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                    <path d="M6 11.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </label>
            <input type="search" id="CurrencyCodeSearch" class="search-input" placeholder="<?= t('Enter currency code') ?>" title="<?= t('Filter currency data') ?>" autocomplete="off" autofocus="autofocus" onclick="this.value=''">
        </form>
        <div class="top-detail-bar">
            <span class="json-last-checked">
                <?= t('Last Checked: %s', $this->dt->datetime($this->model->configModel->get('last_checked_liverates', time() - 86401))) ?>
            </span>
            <a id="PluginBottom" href="#PluginTop" title="<?= t('Go to the bottom of the page') ?>" class="btn-action"><i class="fa fa-level-down" aria-hidden="true"></i> <?= t('Bottom') ?></a>
        </div>
        <table id="CurrenciesTable" class="currencies-table table-row-hover">
            <tr class="table-heading">
                <th class="heading-code column-7"><?= t('Code') ?></th>
                <th class="heading-currency column-30"><?= t('Currency') ?></th>
                <th class="heading-manual-rate column-10"><?= t('Manual Rate') ?></th>
                <th class="heading-last-modfied column-13"><?= t('Last Modified') ?></th>
                <th class="heading-comment"><?= t('Comment') ?></th>
                <th class="heading-live-rate column-10"><?= t('Live Rate') ?></th>
                <th class="heading-live-rate-updated column-14"><?= t('Live Rate Updated') ?></th>
            </tr>
            <?php foreach ($rates as $rate): ?>
                <tr class="rate-results">
                    <td class="row-code">
                        <strong><?= $this->text->e($rate['currency']) ?></strong>
                    </td>
                    <td class="row-currency">
                        <span class="currency-coins-icon"></span> <?= substr($this->text->e($currencies[$rate['currency']]), 6) ?>
                    </td>
                    <td class="row-manual-rate">
                        <?= ($rate['rate'] > 0) ? '<span class="manual-rate-icon"></span> ' . n($rate['rate']) : '' ?>
                    </td>
                    <td class="row-manual-rate-last-modified">
                        <?= ($rate['last_modified'] > 0) ? $this->dt->datetime($rate['last_modified']) : '' ?>
                    </td>
                    <td class="row-manual-rate-comment relative">
                        <?= ($rate['comment']) ?>
                        <?php if (!empty($rate['comment'])): ?>
                            <a href="<?= $this->url->href('CostController', 'editComment', array('plugin' => 'CostControl', 'currency' => $rate['currency'], 'comment' => $rate['comment']), false, '', false) ?>" class="js-modal-small btn edit-comment-btn" title="<?= t('Edit Comment') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sticky-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zm6 8.5a1 1 0 0 1 1-1h4.396a.25.25 0 0 1 .177.427l-5.146 5.146a.25.25 0 0 1-.427-.177V9.5z"/>
                                </svg>
                            </a>
                        <?php else: ?>
                            <a href="<?= $this->url->href('CostController', 'editComment', array('plugin' => 'CostControl', 'currency' => $rate['currency'], 'comment' => $rate['comment']), false, '', false) ?>" class="js-modal-small btn edit-comment-btn edit-comment-empty" title="<?= t('Add Comment') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sticky" viewBox="0 0 16 16">
                                    <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5v-11zm7 11.293V9.5a.5.5 0 0 1 .5-.5h4.293L9 13.793z"/>
                                </svg>
                            </a>
                        <?php endif ?>
                    </td>
                    <td class="row-live-rate">
                        <?= ($rate['live_rate'] > 0) ? '<span class="live-rate-icon"></span> ' . n($rate['live_rate']) : '' ?>
                    </td>
                    <td class="row-live-rate-last-updated">
                        <?= ($rate['live_rate_updated'] > 0) ? $this->dt->datetime($rate['live_rate_updated']) : '' ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <div class="bottom-detail-bar">
            <a id="PluginTop" href="#main" title="<?= t('Go to the top of the page') ?>" class="btn-action"><i class="fa fa-level-up" aria-hidden="true"></i> <?= t('Top') ?></a>
            <a href="https://www.exchangerate-api.com" class="api-detail" target="_blank" title="<?= t('Opens in a new window') ?>" rel="noopener noreferrer">
                <?= t('Live Rates by') ?> <span class="exchange-rate-api-logo"></span>
            </a>
            <span class="last-update-detail">
                <?= t('Last Update to Live Rate Data: %s', $this->dt->datetime($this->model->configModel->get('cost_control_last_updated', time()))) ?>
            </span>
            <span class="next-update-detail">
                <?= t('Next Update to Live Rate Data: %s', $this->dt->datetime($this->model->configModel->get('cost_control_next_update', time()))) ?>
            </span>
        </div>
    <?php endif ?>
</div>
