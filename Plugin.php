<?php

namespace Kanboard\Plugin\CostControl;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Core\Security\Role;
use Kanboard\Plugin\CostControl\Model\ExtendedCurrencyModel;
use Kanboard\Plugin\CostControl\Console\RefreshLiveRatesCommand;
use Kanboard\Plugin\CostControl\Helper\CostControlLayoutHelper;

/**
 * Register CostControl routes, helpers and access boundaries.
 *
 * Related tests: tests/security_contract.php, tests/live_rate_reliability.php,
 * tests/live_rate_integration.php, tests/maintenance_contract.php and
 * tests/static_task_cost_integration.php.
 */
class Plugin extends Base
{
    public function initialize()
    {
        // CSS - Asset Hook
        //  - Keep filename lowercase
        $this->hook->on('template:layout:css', array('template' => 'plugins/CostControl/Assets/css/cost-control.css'));
        $this->hook->on('template:layout:css', array('template' => 'plugins/CostControl/Assets/css/cost-control-icons.css'));

        // JS - Asset Hook
        //  - Keep filename lowercase
        $this->hook->on('template:layout:js', array('template' => 'plugins/CostControl/Assets/js/cost-control.js'));
        if (!is_dir(PLUGINS_DIR . DIRECTORY_SEPARATOR . 'PluginManager')) {
            $this->hook->on('template:layout:js', array('template' => 'plugins/CostControl/Assets/js/cost-control-top-btn.js'));
        }

        // Template Override
        //  - Override name should be camelCase e.g. pluginNameExampleCamelCase
        $this->template->setTemplateOverride('currency/change', 'costControl:currency/change');
        $this->template->setTemplateOverride('currency/create', 'costControl:currency/create');
        $this->template->setTemplateOverride('currency/show', 'costControl:currency/show');

        // Views - Add Menu Item - Template Hook
        //  - Override name should start lowercase e.g. pluginNameExampleCamelCase
        $this->template->hook->attach('template:project:dropdown', 'costControl:project/dropdown');
        $this->template->hook->attach('template:user:sidebar:actions', 'costControl:user/sidebar');
        $this->template->hook->attach('template:config:application', 'costControl:config/settings');
        // TOP RIGHT MENU FOR ALL USERS TO ACCESS
        $this->template->hook->attach('template:header:dropdown', 'costControl:header/user_dropdown');
        // TOP DASHBOARD MENU FOR ALL USERS TO ACCESS
        $this->template->hook->attach('template:dashboard:page-header:menu', 'costControl:dashboard/layout');
        // Reuse Kanboard task hooks for the manager action and status-aware cost detail.
        $this->template->hook->attach('template:task:dropdown:after-basic-actions', 'costControl:task_cost/action');
        $this->template->hook->attach('template:task:sidebar:after-basic-actions', 'costControl:task_cost/action');
        $this->template->hook->attach('template:task:details:third-column', 'costControl:task_cost/details');

        // Extra Page - Routes
        //  - Example: $this->route->addRoute('/my/custom/route', 'MyController', 'show', 'PluginNameExampleStudlyCaps');
        //  - Must have the corresponding action in the matching controller
        $this->route->addRoute('/project/:project_id/budget', 'BudgetController', 'show', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/lines', 'BudgetLineController', 'show', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/lines/add', 'BudgetLineController', 'create', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/breakdown', 'BudgetController', 'breakdown', 'CostControl');
        $this->route->addRoute('/project/:project_id/task/:task_id/static-cost', 'TaskCostController', 'edit', 'CostControl');
        $this->route->addRoute('/user/:user_id/rate', 'HourlyRateController', 'show', 'CostControl');
        $this->route->addRoute('/user/:user_id/rate/add', 'HourlyRateController', 'create', 'CostControl');
        $this->route->addRoute('/user/:user_id/rate/delete/:rate_id', 'HourlyRateController', 'confirm', 'CostControl');
        $this->route->addRoute('/settings/currencies/rates', 'CurrencyController', 'show');
        $this->route->addRoute('/cost-control/currencies/rates', 'CostController', 'showEveryone', 'CostControl');
        $this->route->addRoute('/cost-control/currencies/rates/manual/add', 'CostController', 'create', 'CostControl');
        $this->route->addRoute('/cost-control/currencies/base/change', 'CostController', 'change', 'CostControl');
        $this->route->addRoute('/cost-control/currencies/:currency', 'CostController', 'editComment', 'CostControl');
        // Retain old bookmarks without trusting or using the comment stored in the URL.
        $this->route->addRoute('/cost-control/currencies/:currency/:comment', 'CostController', 'editComment', 'CostControl');

        // Helper
        //  - Example: $this->helper->register('helperClassNameCamelCase', '\Kanboard\Plugin\PluginNameExampleStudlyCaps\Helper\HelperNameExampleStudlyCaps');
        //  - Add each Helper in the 'use' section at the top of this file
        $this->helper->register('costControlLayoutHelper', CostControlLayoutHelper::class);

        $this->applicationAccessMap->add('HourlyRateController', '*', Role::APP_ADMIN);
        // Default CostController actions to administrators, then preserve documented user rate/comment actions.
        $this->applicationAccessMap->add('CostController', '*', Role::APP_ADMIN);
        $this->applicationAccessMap->add('CostController', array('showEveryone', 'create', 'save', 'saveWithoutComment', 'editComment', 'saveCommentForm'), Role::APP_USER);
        $this->projectAccessMap->add('BudgetController', '*', Role::PROJECT_MANAGER);
        $this->projectAccessMap->add('BudgetLineController', '*', Role::PROJECT_MANAGER);
        $this->projectAccessMap->add('TaskCostController', '*', Role::PROJECT_MANAGER);

        // Replace Model
        $this->container['currencyModel'] = $this->container->factory(function ($c) {
            return new ExtendedCurrencyModel($c);
        });

        // Register a scheduler-safe refresh command instead of contacting the provider during web bootstrap.
        $this->cli->add(new RefreshLiveRatesCommand($this->container));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__ . '/Locale');
    }

    public function getClasses()
    {
        return array(
            'Plugin\CostControl\Model' => array(
                'HourlyRate',
                'Budget',
                'TaskCost',
            )
        );
    }

    public function getPluginName()
    {
        // Plugin Name MUST be identical to namespace for Plugin Directory to detect updated versions
        // Do not translate the plugin name here
        return 'CostControl';
    }

    public function getPluginDescription()
    {
        return t('Use the new Cost Control section to enable currencies and budgeting in Kanboard. Get live currency rates automatically for over 120 currencies allowing users to compare with manually saved rates. This plugin replaces and extends the features from the original Budget plugin enabling projects to have an associated cost element.');
    }

    public function getPluginAuthor()
    {
        return 'aljawaid';
    }

    public function getPluginVersion()
    {
        return '1.2.4';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.54';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/aljawaid/CostControl';
    }
}
