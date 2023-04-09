<div class="page-header">
    <h2 class=""><?= t('Add or change currency rate') ?></h2>
</div>
<form method="post" class="" action="<?= $this->url->href('CostController', 'save', array('plugin' => 'CostControl')) ?>" autocomplete="on">
    <?= $this->form->csrf() ?>
    <?= $this->form->label(t('Currency'), 'currency') ?>
    <?= $this->form->select('currency', $currencies, $values, $errors) ?>
    <?= $this->form->label(t('Rate'), 'rate') ?>
    <?= $this->form->text('rate', $values, $errors, array('autofocus'), 'form-numeric') ?>
    <?php if ($this->task->configModel->get('cost_control_rate_comments', '') == 'replace_comment_on_edit'): ?>
        <?= $this->form->label(t('New Comment'), 'comment') ?>
        <?= $this->form->text('comment', $values, array(), array('placeholder="Provider Notes"'), 'form-text') ?>
    <?php endif ?>
    <?= $this->modal->submitButtons() ?>
</form>
