<?php

// Related visual coverage: tests/visual_contract.php.

?>

<li class="cost-control-dropdown">
    <a href="<?= $this->url->href('CostController', 'showEveryone', array('plugin' => 'CostControl')) ?>" rel="noopener noreferrer">
        <img src="<?= $this->url->dir() ?>plugins/CostControl/Assets/icons/currency-wallet-icon.svg" class="cost-control-navigation-icon" width="16" height="16" alt="" aria-hidden="true">
        <?= t('Cost Control') ?>
    </a>
</li>
