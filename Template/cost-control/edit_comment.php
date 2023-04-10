<div class="page-header">
    <h2 class=""><?= t('Edit Comment') ?></h2>
</div>
<form method="post" class="" action="<?= $this->url->href('CostController', 'editCommentForm', array('plugin' => 'CostControl')) ?>" autocomplete="on">
    <?= $this->form->csrf() ?>
</form>
