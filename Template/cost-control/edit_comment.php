<div id="EditCommentModal" class="cost-control-page-header-comment">
    <?php if (!empty($comment)): ?>
        <h2 class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sticky-fill" viewBox="0 0 16 16">
                <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zm6 8.5a1 1 0 0 1 1-1h4.396a.25.25 0 0 1 .177.427l-5.146 5.146a.25.25 0 0 1-.427-.177V9.5z"/>
            </svg> <?= e('Edit Comment for %s', '<strong>' . $currency . '</strong>') ?>
        </h2>
        <form method="post" class="edit-create-comment" action="<?= $this->url->href('CostController', 'saveCommentForm', array('plugin' => 'costControl')) ?>" autocomplete="on">
            <?= $this->form->csrf() ?>
            <input type="hidden" id="currency" name="currency" value="<?= $currency ?>">
            <?= $this->form->label(t('Edit Comment'), 'comment') ?>
            <?= $this->form->text('comment', $values, array(), array('placeholder="' . $comment . '"', 'value="' . $comment . '"'), 'form-text') ?>
            <p class="form-help"><?= t('Leave empty to clear the comment') ?></p>
            <?= $this->modal->submitButtons() ?>
        </form>
    <?php else: ?>
        <h2 class="">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sticky" viewBox="0 0 16 16">
                <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5v-11zm7 11.293V9.5a.5.5 0 0 1 .5-.5h4.293L9 13.793z"/>
            </svg> <?= e('Add Comment for %s', '<strong>' . $currency . '</strong>') ?>
        </h2>
        <form method="post" class="edit-create-comment" action="<?= $this->url->href('CostController', 'saveCommentForm', array('plugin' => 'costControl')) ?>" autocomplete="on">
            <?= $this->form->csrf() ?>
            <input type="hidden" id="currency" name="currency" value="<?= $currency ?>">
            <?= $this->form->label(t('New Comment'), 'comment') ?>
            <?= $this->form->text('comment', $values, array(), array('placeholder="' . t('Add a short note for this currency') . '"', 'value="' . $comment . '"'), 'form-text') ?>
            <p class="form-help ml-0">
                <?= t('Leaving this comment blank or entering a new comment will replace any previously saved comment for this currency code') ?>
            </p>
            <?= $this->modal->submitButtons() ?>
        </form>
    <?php endif ?>
</div>
