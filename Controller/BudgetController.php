<?php

namespace Kanboard\Plugin\CostControl\Controller;

use Kanboard\Controller\BaseController;

/**
 * Budget
 *
 * @package controller
 * @author  Frederic Guillot
 */
class BudgetController extends BaseController
{
    public function show()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('costControl:budget/show', array(
            'daily_budget' => $this->budget->getDailyBudgetBreakdown($project['id']),
            'project' => $project,
            'title' => t('Budget')
        ), 'costControl:budget/sidebar'));
    }

    public function breakdown()
    {
        $project = $this->getProject();

        $paginator = $this->paginator
            ->setUrl('BudgetController', 'breakdown', array('plugin' => 'CostControl', 'project_id' => $project['id']))
            ->setMax(30)
            ->setOrder('start')
            ->setDirection('DESC')
            ->setQuery($this->budget->getSubtaskBreakdown($project['id']))
            ->calculate();

        $this->response->html($this->helper->layout->project('costControl:budget/breakdown', array(
            'paginator' => $paginator,
            'project' => $project,
            'title' => t('Budget')
        ), 'costControl:budget/sidebar'));
    }
}
