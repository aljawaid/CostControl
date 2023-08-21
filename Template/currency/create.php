<div id="CreateRateModal" class="cost-control-page-header-create">
    <h2 class="">
        <span class="manual-rate-icon"></span> <?= t('Update Manual Rate') ?>
    </h2>
    <?php if ($this->task->configModel->get('cost_control_rate_comments', '') == 'replace_comment_on_edit'): ?>
        <form method="post" class="create-rate-comment" action="<?= $this->url->href('CostController', 'save', array('plugin' => 'CostControl')) ?>" autocomplete="on">
            <?= $this->form->csrf() ?>
            <?= $this->form->label(t('Select Currency'), 'currency') ?>
            <?= $this->form->select('currency', $currencies, $values, $errors) ?>
            <?= $this->form->label(t('Enter Rate'), 'rate') ?>
            <?= $this->form->number('rate', $values, $errors, array('autofocus placeholder="1.00"'), 'form-numeric') ?>
            <p class="form-help"><?= t('Enter \'0\' to clear the previous rate') ?></p>
            <?= $this->form->label(t('New Comment'), 'comment') ?>
            <?= $this->form->text('comment', $values, array(), array('placeholder="' . t('Add a short note for this currency') . '"'), 'form-text') ?>
            <p class="form-help"><?= t('Leaving this comment blank or entering a new comment will replace any previously saved comment for this currency code') ?></p>
            <?= $this->modal->submitButtons() ?>
        </form>
    <?php else: ?>
        <form method="post" class="create-rate-comment" action="<?= $this->url->href('CostController', 'saveWithoutComment', array('plugin' => 'CostControl')) ?>" autocomplete="on">
            <?= $this->form->csrf() ?>
            <?= $this->form->label(t('Select Currency'), 'currency') ?>
            <?= $this->form->select('currency', $currencies, $values, $errors) ?>
            <?= $this->form->label(t('Enter Rate'), 'rate') ?>
            <?= $this->form->number('rate', $values, $errors, array('autofocus placeholder="1.00"'), 'form-numeric') ?>
            <p class="form-help"><?= t('Enter \'0\' to clear the previous rate') ?></p>
            <?= $this->modal->submitButtons() ?>
        </form>
    <?php endif ?>
</div>
