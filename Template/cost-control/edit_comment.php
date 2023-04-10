<div class="page-header">
    <h2 class=""><?= t('Edit Comment') ?></h2>
</div>
<form method="post" class="" action="<?= $this->url->href('CostController', 'saveCommentForm', array('plugin' => 'costControl')) ?>" autocomplete="on">
    <?= $this->form->csrf() ?>

    <input type="hidden" id="currency" name="currency" value="<?= $currency ?>">
    edit comment for: <?= $currency ?>

    <?= $this->form->label(t('New Comment'), 'comment') ?>
    <?= $this->form->text('comment', $values, array(), array('placeholder="'. $comment .'"', 'value="'. $comment .'"'), 'form-text') ?>
    <?= $this->modal->submitButtons() ?>
</form>
