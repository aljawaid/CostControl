<?php

namespace Kanboard\Plugin\CostControl\Controller;

use Kanboard\Controller\BaseController;

/**
 * Budget and static task cost summaries.
 *
 * @package controller
 * @author  Frederic Guillot
 * @see tests/static_task_cost_integration.php
 */
class BudgetController extends BaseController
{
    public function show()
    {
        $project = $this->getProject();
        $taskCostSummary = $this->taskCost->getProjectSummary($project['id']);
        $taskCostSummary['budget'] = $this->budget->getTotal($project['id']);

        $this->response->html($this->helper->layout->project('costControl:budget/show', array(
            'daily_budget' => $this->budget->getDailyBudgetBreakdown($project['id']),
            'project' => $project,
            'task_cost_summary' => $taskCostSummary,
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
