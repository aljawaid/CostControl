<div class="sidebar cost-control-sidebar">
    cost control
    <ul class="cost-control-menu">
        <li <?= $this->app->checkMenuSelection('CostController') ?>>
            <?= $this->url->link(t('Currency Rates'), 'CostController', 'showEveryone', array('plugin' => 'CostControl'), false, 'cost-control-menu-item') ?>
        </li>
        <?= $this->hook->render('template:costcontrol:sidebar') ?>
    </ul>
</div>