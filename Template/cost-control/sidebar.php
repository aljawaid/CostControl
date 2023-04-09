<div class="sidebar cost-control-sidebar">
    <ul class="cost-control-menu">
        <li <?= $this->app->checkMenuSelection('CostController') ?>>
            <?= $this->url->link(t('Currency Rates'), 'CostController', 'showEveryone', array('plugin' => 'CostControl'), false, 'cost-control-menu-item') ?>
        </li>
        <li class="">
            <?= $this->url->link(t('Settings'), 'ConfigController', 'application', array(), false, '', '', false, 'CostControlSettings') ?>
        </li>
        <?= $this->hook->render('template:cost-control:sidebar') ?>
    </ul>
</div>
