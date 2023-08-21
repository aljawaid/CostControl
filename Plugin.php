<?php

namespace Kanboard\Plugin\CostControl;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Core\Security\Role;
use Kanboard\Plugin\CostControl\Model\ExtendedCurrencyModel;
use Kanboard\Model\CurrencyModel;
use Kanboard\Plugin\CostControl\Helper\CostControlLayoutHelper;
use Kanboard\Helper;

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
        if (!file_exists('plugins/PluginManager')) {
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

        // Extra Page - Routes
        //  - Example: $this->route->addRoute('/my/custom/route', 'MyController', 'show', 'PluginNameExampleStudlyCaps');
        //  - Must have the corresponding action in the matching controller
        $this->route->addRoute('/project/:project_id/budget', 'BudgetController', 'show', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/lines', 'BudgetLineController', 'show', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/lines/add', 'BudgetLineController', 'create', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/breakdown', 'BudgetController', 'breakdown', 'CostControl');
        $this->route->addRoute('/user/:user_id/rate', 'HourlyRateController', 'show', 'CostControl');
        $this->route->addRoute('/user/:user_id/rate/add', 'HourlyRateController', 'create', 'CostControl');
        $this->route->addRoute('/user/:user_id/rate/delete/:rate_id', 'HourlyRateController', 'confirm', 'CostControl');
        $this->route->addRoute('/settings/currencies/rates', 'CurrencyController', 'show');
        $this->route->addRoute('/cost-control/currencies/rates', 'CostController', 'showEveryone', 'CostControl');
        $this->route->addRoute('/cost-control/currencies/rates/manual/add', 'CostController', 'create', 'CostControl');
        $this->route->addRoute('/cost-control/currencies/base/change', 'CostController', 'change', 'CostControl');
        $this->route->addRoute('/cost-control/currencies/:currency/:comment', 'CostController', 'editComment', 'CostControl');

        // Helper
        //  - Example: $this->helper->register('helperClassNameCamelCase', '\Kanboard\Plugin\PluginNameExampleStudlyCaps\Helper\HelperNameExampleStudlyCaps');
        //  - Add each Helper in the 'use' section at the top of this file
        $this->helper->register('costControlLayoutHelper', '\Kanboard\Plugin\CostControl\Helper\CostControlLayoutHelper');

        $this->applicationAccessMap->add('HourlyRateController', '*', Role::APP_ADMIN);
        $this->projectAccessMap->add('BudgetController', '*', Role::PROJECT_MANAGER);

        // Replace Model
        $this->container['currencyModel'] = $this->container->factory(function ($c) {
                return new ExtendedCurrencyModel($c);
        });

        // CHECK FOR LIVE RATES ONE MIN AFTER NEXT SCHEDULED UPDATE FROM THE JSON
        if (time() - $this->configModel->get('cost_control_next_update', time() - 61) > 60) {
            // SAVE DATE WHEN CHECKED
            $this->configModel->save(['cost_control_last_checked_live_rates' => time()]);
            // CHECK JSON API
            $this->currencyModel->getLiveRates();
        }
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
        return '1.2.0';
    }

    public function getCompatibleVersion()
    {
        // Examples: '>=1.0.37' '<1.0.37' '<=1.0.37'
        // Tested on KB v1.2.20 upto plugin v1.1.0, then KB v1.2.32+
        return '>=1.2.20';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/aljawaid/CostControl';
    }
}
