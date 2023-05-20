<?php

namespace Kanboard\Plugin\CostControl\Helper;

use Kanboard\Core\Base;

/**
 * CostControlLayout Helper
 *
 * @package helper
 * @author  Frederic Guillot
 */
class CostControlLayoutHelper extends Base
{
    /**
     * Common layout for config views
     *
     * @access public
     * @param  string $template
     * @param  array  $params
     * @return string
     */
    public function customLayout($template, array $params)
    {
        if (!isset($params['values'])) {
            $params['values'] = $this->configModel->getAll();
        }

        if (!isset($params['errors'])) {
            $params['errors'] = array();
        }

        return $this->helper->layout->subLayout('dashboard/layout', 'costControl:cost-control/sidebar', $template, $params);
    }
}
