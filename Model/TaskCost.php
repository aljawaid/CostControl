<?php

namespace Kanboard\Plugin\CostControl\Model;

use Kanboard\Core\Base;
use Kanboard\Model\TaskModel;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Store fixed task costs and classify them from Kanboard's task status.
 *
 * Related test: tests/static_task_cost_integration.php.
 */
class TaskCost extends Base
{
    public const TABLE = 'cost_control_task_costs';
    public const MAX_FINANCIAL_VALUE = 9999999999999;

    /**
     * Get a task cost within its authorised project.
     *
     * @param integer $taskId
     * @param integer $projectId
     * @return array
     */
    public function getByTaskId($taskId, $projectId)
    {
        return $this->db->table(self::TABLE)
            ->columns(self::TABLE . '.*')
            ->join(TaskModel::TABLE, 'id', 'task_id')
            ->eq(self::TABLE . '.task_id', $taskId)
            ->eq(TaskModel::TABLE . '.project_id', $projectId)
            ->findOne();
    }

    /**
     * Get project totals split by the current Kanboard task status.
     *
     * @param integer $projectId
     * @return array
     */
    public function getProjectSummary($projectId)
    {
        $summary = array(
            'pending' => 0.0,
            'confirmed' => 0.0,
        );

        $records = $this->db->table(self::TABLE)
            ->columns(self::TABLE . '.amount', TaskModel::TABLE . '.is_active')
            ->join(TaskModel::TABLE, 'id', 'task_id')
            ->eq(TaskModel::TABLE . '.project_id', $projectId)
            ->findAll();

        foreach ($records as $record) {
            $key = (int) $record['is_active'] === 1 ? 'pending' : 'confirmed';
            $summary[$key] = round($summary[$key] + (float) $record['amount'], 6);
        }

        return $summary;
    }

    /**
     * Create or update a cost only when the task belongs to the route project.
     *
     * @param integer $taskId
     * @param integer $projectId
     * @param mixed $amount
     * @return boolean|integer
     */
    public function save($taskId, $projectId, $amount)
    {
        $task = $this->taskFinderModel->getById($taskId);

        if (empty($task) || (int) $task['project_id'] !== (int) $projectId) {
            return false;
        }

        $values = array(
            'amount' => $this->normalizeFinancialValue($amount),
        );
        $existing = $this->getByTaskId($taskId, $projectId);

        if (! empty($existing)) {
            return $this->db->table(self::TABLE)
                ->eq('task_id', $taskId)
                ->update($values);
        }

        $values['task_id'] = $taskId;
        return $this->db->table(self::TABLE)->insert($values);
    }

    /**
     * Remove a task cost only when the task belongs to the route project.
     *
     * @param integer $taskId
     * @param integer $projectId
     * @return boolean
     */
    public function remove($taskId, $projectId)
    {
        if (empty($this->getByTaskId($taskId, $projectId))) {
            return false;
        }

        return $this->db->table(self::TABLE)
            ->eq('task_id', $taskId)
            ->remove();
    }

    /**
     * Validate a non-negative fixed task cost.
     *
     * @param array $values
     * @return array
     */
    public function validate(array $values)
    {
        $validator = new Validator($values, array(
            new Validators\Required('amount', t('Field required')),
            new Validators\Numeric('amount', t('This value must be numeric')),
            new Validators\Range('amount', t('This value is outside the supported financial range'), 0, self::MAX_FINANCIAL_VALUE),
        ));

        return array(
            $validator->execute(),
            $validator->getErrors(),
        );
    }

    /**
     * Normalise financial input to the database scale.
     *
     * @param mixed $value
     * @return string
     */
    private function normalizeFinancialValue($value)
    {
        return number_format(round((float) $value, 6), 6, '.', '');
    }
}
