<fieldset id="CostControlSettings" class="panel c-control-settings">
    <h3 class="">
        <span class="currency-wallet-icon"></span> Cost Control <?= t('Settings') ?>
    </h3>
    <fieldset class="glancer-options">
        <legend class=""><?= t('Currency Options') ?></legend>
        <p class="glancer-options-intro"><?= t('Setting a reference currency here allows you to view and compare the reference currency to the base currency at a glance around the site') ?></p>
        <div class="glancer-options-section-area">
            <div class="glancer-options-section w-200">
                <?= $this->form->label(t('Reference Currency'), 'cost_control_reference_currency', array('class="w-200"')) ?>
                <?= $this->form->text('cost_control_reference_currency', $values, $errors, array('placeholder="EUR"')) ?>
                <p class="form-help w-200"><?= t('Enter the three letter currency code') ?></p>
            </div>
        </div>
    </fieldset>
    <fieldset class="c-control-options">
        <legend class=""><?= t('Comment Options') ?></legend>
        <p class="c-control-options-intro"><?= t('Control how comments are saved against manual rates') ?></p>
        <div class="c-control-options-section-radios">
            <div class="c-control-radio-options">
                <?= $this->form->radio('cost_control_rate_comments', t('Replace the previous comment when editing a manual rate'), 'replace_comment_on_edit', true, '', isset($values['cost_control_rate_comments']) && $values['cost_control_rate_comments'] == 'replace_comment_on_edit') ?>
                <?= $this->form->radio('cost_control_rate_comments', t('Keep the previous comment when editing a manual rate'), 'ignore_comment_on_edit', isset($values['cost_control_rate_comments']) && $values['cost_control_rate_comments'] == 'ignore_comment_on_edit') ?>
            </div>
        </div>
    </fieldset>
    <div class="form-actions">
        <button type="submit" class="btn btn-blue" title="<?= t('Saves any changes made on this page') ?>"><?= t('Save Settings') ?></button>
        <a href="<?= $this->url->href('CostController', 'showEveryone', array('plugin' => 'CostControl')) ?>" class="btn cost-control-btn" title="<?= t('Go to Cost Control') ?>"><span class="currency-wallet-icon"></span> <?= t('Cost Control') ?></a>
    </div>
</fieldset>
