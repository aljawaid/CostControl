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
    <div class="form-actions">
        <button type="submit" class="btn btn-blue" title="<?= t('Saves any changes made on this page') ?>"><?= t('Save Settings') ?></button>
    </div>
</fieldset>
