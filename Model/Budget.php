<?php

namespace Kanboard\Plugin\CostControl\Model;

use DateInterval;
use DateTime;
use SimpleValidator\Validator;
use SimpleValidator\Validators;
use Kanboard\Core\Base;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\SubtaskModel;

/**
 * Budget
 *
 * @package  model
 * @author   Frederic Guillot
 * @see tests/security_contract.php
 * @see tests/financial_integrity.php
 * @see tests/financial_model_integration.php
 */
class Budget extends Base
{
    public const MAX_FINANCIAL_VALUE = 9999999999999;

    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'budget_lines';

    /**
     * Get all budget lines for a project
     *
     * @access public
     * @param  integer   $project_id
     * @return array
     */
    public function getAll($project_id)
    {
        return $this->db->table(self::TABLE)->eq('project_id', $project_id)->desc('date')->findAll();
    }

    /**
     * Get a budget line only when it belongs to the authorized project.
     *
     * @param integer $budget_id
     * @param integer $project_id
     * @return array
     */
    public function getById($budget_id, $project_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('id', $budget_id)
            ->eq('project_id', $project_id)
            ->findOne();
    }

    /**
     * Get the current total of the budget
     *
     * @access public
     * @param  integer   $project_id
     * @return float
     */
    public function getTotal($project_id)
    {
        $result = $this->db->table(self::TABLE)->columns('SUM(amount) as total')->eq('project_id', $project_id)->findOne();
        return isset($result['total']) ? round((float) $result['total'], 6) : 0.0;
    }

    /**
     * Get breakdown by tasks/subtasks/users
     *
     * @access public
     * @param  integer    $project_id
     * @return \PicoDb\Table
     */
    public function getSubtaskBreakdown($project_id)
    {
        return $this->db
                    ->table(SubtaskTimeTrackingModel::TABLE)
                    ->columns(
                        SubtaskTimeTrackingModel::TABLE . '.id',
                        SubtaskTimeTrackingModel::TABLE . '.user_id',
                        SubtaskTimeTrackingModel::TABLE . '.subtask_id',
                        SubtaskTimeTrackingModel::TABLE . '.start',
                        SubtaskTimeTrackingModel::TABLE . '.time_spent',
                        SubtaskModel::TABLE . '.task_id',
                        SubtaskModel::TABLE . '.title AS subtask_title',
                        TaskModel::TABLE . '.title AS task_title',
                        TaskModel::TABLE . '.project_id',
                        UserModel::TABLE . '.username',
                        UserModel::TABLE . '.name'
                    )
                    ->join(SubtaskModel::TABLE, 'id', 'subtask_id')
                    ->join(TaskModel::TABLE, 'id', 'task_id', SubtaskModel::TABLE)
                    ->join(UserModel::TABLE, 'id', 'user_id')
                    ->eq(TaskModel::TABLE . '.project_id', $project_id)
                    ->callback(array($this, 'applyUserRate'));
    }

    /**
     * Gather necessary information to display the budget graph
     *
     * @access public
     * @param  integer  $project_id
     * @return array
     */
    public function getDailyBudgetBreakdown($project_id)
    {
        $out = array();
        $in = array();
        $budgetLines = $this->db->table(self::TABLE)->eq('project_id', $project_id)->asc('date')->findAll();
        $time_slots = $this->getSubtaskBreakdown($project_id)->findAll();

        foreach ($budgetLines as $line) {
            if (!isset($in[$line['date']])) {
                $in[$line['date']] = 0.0;
            }

            // Multiple adjustments on one day must be accumulated without dropping fractional values.
            $in[$line['date']] = round($in[$line['date']] + (float) $line['amount'], 6);
        }

        foreach ($time_slots as $slot) {
            $date = date('Y-m-d', $slot['start']);

            if (!isset($out[$date])) {
                $out[$date] = 0;
            }

            $out[$date] = round($out[$date] + (float) $slot['cost'], 6);
        }

        $start = key($in) ?: key($out);
        $end = new DateTime();
        $left = 0.0;
        $serie = array();

        for ($today = new DateTime($start); $today <= $end; $today->add(new DateInterval('P1D'))) {
            $date = $today->format('Y-m-d');
            $today_in = isset($in[$date]) ? (float) $in[$date] : 0.0;
            $today_out = isset($out[$date]) ? (float) $out[$date] : 0.0;

            if ($today_in != 0.0 || $today_out != 0.0) {
                $left = round($left + $today_in - $today_out, 6);

                $serie[] = array(
                    'date' => $date,
                    'in' => round($today_in, 6),
                    'out' => round(-$today_out, 6),
                    'left' => $left,
                );
            }
        }

        return $serie;
    }

    /**
     * Filter callback to apply the rate according to the effective date
     *
     * @access public
     * @param  array   $records
     * @return array
     */
    public function applyUserRate(array $records)
    {
        if (empty($records)) {
            return array();
        }

        $rates = $this->hourlyRate->getAllByProject($records[0]['project_id']);

        foreach ($records as &$record) {
            $hourly_price = 0;

            foreach ($rates as $rate) {
                if ($rate['user_id'] == $record['user_id'] && date('Y-m-d', $rate['date_effective']) <= date('Y-m-d', $record['start'])) {
                    $hourly_price = $this->currencyModel->getPrice($rate['currency'], $rate['rate']);
                    break;
                }
            }

            $record['cost'] = round((float) $hourly_price * (float) $record['time_spent'], 6);
        }

        return $records;
    }

    /**
     * Add a new budget line in the database
     *
     * @access public
     * @param  integer   $project_id
     * @param  float     $amount
     * @param  string    $comment
     * @param  string    $date
     * @return boolean|integer
     */
    public function create($project_id, $amount, $comment, $date = '')
    {
        $values = array(
            'project_id' => $project_id,
            'amount' => $this->normalizeFinancialValue($amount),
            'comment' => $comment,
            'date' => $date ?: date('Y-m-d'),
        );

        return $this->db->table(self::TABLE)->persist($values);
    }

    /**
     * Remove a specific budget line
     *
     * @access public
     * @param  integer    $budget_id
     * @param  integer    $project_id
     * @return boolean
     */
    public function remove($budget_id, $project_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('id', $budget_id)
            ->eq('project_id', $project_id)
            ->remove();
    }

    /**
     * Validate creation
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $v = new Validator($values, array(
            new Validators\Required('project_id', t('Field required')),
            new Validators\Required('amount', t('Field required')),
            new Validators\Numeric('amount', t('This value must be numeric')),
            new Validators\Range('amount', t('This value is outside the supported financial range'), -self::MAX_FINANCIAL_VALUE, self::MAX_FINANCIAL_VALUE),
            new Validators\Required('date', t('Field required')),
            new Validators\Date('date', t('Invalid date'), $this->dateParser->getParserFormats()),
        ));

        return array(
            $v->execute(),
            $v->getErrors()
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
