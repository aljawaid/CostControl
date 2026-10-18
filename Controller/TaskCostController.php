<?php

namespace Kanboard\Plugin\CostControl\Controller;

use Kanboard\Controller\BaseController;

/**
 * Manage project-manager-only static costs for individual tasks.
 *
 * Related test: tests/static_task_cost_integration.php.
 */
class TaskCostController extends BaseController
{
    /**
     * Show the static task cost form.
     *
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $task = $this->getTask();
        $taskCost = $this->taskCost->getByTaskId($task['id'], $project['id']);

        if (empty($values)) {
            $values = array(
                'amount' => isset($taskCost['amount']) ? $taskCost['amount'] : '',
            );
        }

        $this->response->html($this->template->render('costControl:task_cost/edit', array(
            'application_currency' => $this->configModel->get('application_currency'),
            'errors' => $errors,
            'project' => $project,
            'task' => $task,
            'task_cost' => $taskCost,
            'values' => $values,
        )));
    }

    /**
     * Validate and save a static task cost.
     */
    public function save()
    {
        $this->checkCSRFForm();
        $project = $this->getProject();
        $task = $this->getTask();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->taskCost->validate($values);

        if ($valid && $this->taskCost->save($task['id'], $project['id'], $values['amount'])) {
            $this->flash->success(t('Static task cost saved successfully.'));
            $this->redirectToTask($task['id']);
            return;
        }

        if ($valid) {
            $this->flash->failure(t('Unable to save the static task cost.'));
        }

        $this->edit($values, $errors);
    }

    /**
     * Confirm removal without changing data on a GET request.
     */
    public function confirm()
    {
        $project = $this->getProject();
        $task = $this->getTask();

        if (empty($this->taskCost->getByTaskId($task['id'], $project['id']))) {
            $this->flash->failure(t('Unable to remove the static task cost.'));
            $this->redirectToTask($task['id']);
            return;
        }

        $this->response->html($this->template->render('costControl:task_cost/remove', array(
            'project' => $project,
            'task' => $task,
        )));
    }

    /**
     * Remove a static task cost.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $project = $this->getProject();
        $task = $this->getTask();

        if ($this->taskCost->remove($task['id'], $project['id'])) {
            $this->flash->success(t('Static task cost removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove the static task cost.'));
        }

        $this->redirectToTask($task['id']);
    }

    /**
     * Return to the task after a modal write.
     *
     * @param integer $taskId
     */
    private function redirectToTask($taskId)
    {
        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array(
            'task_id' => $taskId,
        )), true);
    }
}
