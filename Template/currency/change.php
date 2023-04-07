<div class="page-header">
    <h2 class=""><?= t('Change Base Currency') ?></h2>
</div>
<form method="post" class="" action="<?= $this->url->href('CurrencyController', 'update') ?>" autocomplete="on">

    <?= $this->form->csrf() ?>
    <?= $this->form->label(t('Base Currency'), 'application_currency') ?>
    <?= $this->form->select('application_currency', $currencies, $values, $errors) ?>
    <?= $this->modal->submitButtons() ?>
</form>
