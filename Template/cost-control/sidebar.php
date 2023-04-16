<div id="CostControlSidebar" class="sidebar cost-control-sidebar">
    <ul class="cost-control-menu">
        <li <?= $this->app->checkMenuSelection('CostController') ?>>
            <?= $this->url->link(t('Currency Rates'), 'CostController', 'showEveryone', array('plugin' => 'CostControl'), false, 'cost-control-menu-item') ?>
        </li>
        <?php if ($this->user->hasAccess('HourlyRateController', 'index')): ?>
            <li <?= $this->app->getRouterController() === 'HourlyRateController' ? 'class="active"' : '' ?>>
                <?= $this->url->link(t('Hourly rate'), 'HourlyRateController', 'show', array('plugin' => 'CostControl', 'user_id' => $user['id'])) ?>
            </li>
        <?php endif ?>
        <?php if ($this->user->hasAccess('ConfigController', 'application')): ?>
            <li class="">
                <?= $this->url->link(t('Settings'), 'ConfigController', 'application', array(), false, '', '', false, 'CostControlSettings') ?>
            </li>
        <?php endif ?>
        <?= $this->hook->render('template:cost-control:sidebar') ?>
    </ul>
</div>
