<?php

namespace Kanboard\Plugin\CostControl;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Core\Security\Role;

class Plugin extends Base
{
    public function initialize()
    {
        $this->applicationAccessMap->add('HourlyRateController', '*', Role::APP_ADMIN);
        $this->projectAccessMap->add('BudgetController', '*', Role::PROJECT_MANAGER);

        // Views - Add Menu Item - Template Hook
        //  - Override name should start lowercase e.g. pluginNameExampleCamelCase
        $this->template->hook->attach('template:project:dropdown', 'costControl:project/dropdown');
        $this->template->hook->attach('template:user:sidebar:actions', 'costControl:user/sidebar');

        // Extra Page - Routes
        //  - Example: $this->route->addRoute('/my/custom/route', 'MyController', 'show', 'PluginNameExampleStudlyCaps');
        //  - Must have the corresponding action in the matching controller
        $this->route->addRoute('/budget/project/:project_id', 'BudgetController', 'show', 'budget');
        $this->route->addRoute('/budget/project/:project_id/lines', 'BudgetLineController', 'show', 'budget');
        $this->route->addRoute('/budget/project/:project_id/breakdown', 'BudgetController', 'breakdown', 'budget');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getClasses()
    {
        return array(
            'Plugin\Budget\Model' => array(
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
        return t('description text');
    }

    public function getPluginAuthor()
    {
        return '';
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
