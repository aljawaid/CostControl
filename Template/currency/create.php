<div class="page-header">
    <h2 class=""><?= t('Add or change currency rate') ?></h2>
</div>
<form method="post" class="" action="<?= $this->url->href('CostController', 'save', array('plugin' => 'CostControl')) ?>" autocomplete="on">
    <?= $this->form->csrf() ?>
    <?= $this->form->label(t('Currency'), 'currency') ?>
    <?= $this->form->select('currency', $currencies, $values, $errors) ?>
    <?= $this->form->label(t('Rate'), 'rate') ?>
    <?= $this->form->text('rate', $values, $errors, array('autofocus'), 'form-numeric') ?>
    <?= $this->form->label(t('Comment'), 'comment') ?>
    <?= $this->form->text('comment', $values, array(), array('placeholder="Provider Notes"'), 'form-text') ?>
    <?= $this->modal->submitButtons() ?>
</form>
