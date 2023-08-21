<div id="ChangeModal" class="cost-control-page-header-change">
    <h2 class="">
        <span class="base-rate-home-icon"></span> <?= t('Change Base Currency') ?>
    </h2>
    <form method="post" class="change-base-rate" action="<?= $this->url->href('CostController', 'update', array('plugin' => 'CostControl')) ?>" autocomplete="on">
        <?= $this->form->csrf() ?>
        <?= $this->form->label(t('Base Currency'), 'application_currency') ?>
        <?= $this->form->select('application_currency', $currencies, $values, $errors) ?>
        <p class="form-help">
            <?= t('The application currency is known as the base currency and associates the base rate (1.00) against other currencies') ?>
        </p>
        <?= $this->modal->submitButtons() ?>
    </form>
</div>
