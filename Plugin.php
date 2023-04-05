<?php

namespace Kanboard\Plugin\CostControl;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Core\Security\Role;
use Kanboard\Plugin\CostControl\Model\ExtendedCurrencyModel;
//use Kanboard\Plugin\CostControl\LiveRateHelper;  // Helper Class and Filename should be exact

class Plugin extends Base
{
    public function initialize()
    {
        // CSS - Asset Hook
        //  - Keep filename lowercase
        $this->hook->on('template:layout:css', array('template' => 'plugins/CostControl/Assets/css/cost-control.css'));

        // Template Override
        //  - Override name should be camelCase e.g. pluginNameExampleCamelCase
        //$this->template->setTemplateOverride('currency/change', 'costControl:currency/change');
        //$this->template->setTemplateOverride('currency/create', 'costControl:currency/create');
        $this->template->setTemplateOverride('currency/show', 'costControl:currency/show');

        // Views - Add Menu Item - Template Hook
        //  - Override name should start lowercase e.g. pluginNameExampleCamelCase
        $this->template->hook->attach('template:project:dropdown', 'costControl:project/dropdown');
        $this->template->hook->attach('template:user:sidebar:actions', 'costControl:user/sidebar');

        // Extra Page - Routes
        //  - Example: $this->route->addRoute('/my/custom/route', 'MyController', 'show', 'PluginNameExampleStudlyCaps');
        //  - Must have the corresponding action in the matching controller
        $this->route->addRoute('/project/:project_id/budget', 'BudgetController', 'show', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/lines', 'BudgetLineController', 'show', 'CostControl');
        $this->route->addRoute('/project/:project_id/budget/breakdown', 'BudgetController', 'breakdown', 'CostControl');
        $this->route->addRoute('/settings/currencies/rates', 'CurrencyController', 'show');
        $this->route->addRoute('/settings/currencies/add', 'CurrencyController', 'create');
        $this->route->addRoute('/settings/currencies/change', 'CurrencyController', 'change');

        // Helper
        //  - Example: $this->helper->register('helperClassNameCamelCase', '\Kanboard\Plugin\PluginNameExampleStudlyCaps\Helper\HelperNameExampleStudlyCaps');
        //  - Add each Helper in the 'use' section at the top of this file
        //$this->helper->register('liveRateHelper', '\Kanboard\Plugin\CostControl\Helper\LiveRateHelper');

        $this->applicationAccessMap->add('HourlyRateController', '*', Role::APP_ADMIN);
        $this->projectAccessMap->add('BudgetController', '*', Role::PROJECT_MANAGER);

        // Replace Model
        $this->container['currencyModel'] = $this->container->factory(function ($c) {
                return new ExtendedCurrencyModel($c);
        });
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
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
        return t('Manage your costs per project to budget and plan using over 100 currencies. Get automatic live daily updates to compare your saved currency rates to forecast your budgets more accurately. This plugin replaces and extends the features from the original Budget plugin. Extended features include allowing users to change and modify their base currencies for most countries. ');
    }

    public function getPluginAuthor()
    {
        return 'aljawaid';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getCompatibleVersion()
    {
        // Examples:
        // >=1.0.37
        // <1.0.37
        // <=1.0.37
        return '>=1.2.20';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/aljawaid/CostControl';
    }
}
