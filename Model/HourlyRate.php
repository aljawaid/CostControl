<?php

namespace Kanboard\Plugin\CostControl\Model;

use Kanboard\Core\Base;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Hourly Rate
 *
 * @package  model
 * @author   Frederic Guillot
 * @see tests/financial_integrity.php
 * @see tests/financial_model_integration.php
 */
class HourlyRate extends Base
{
    public const MAX_FINANCIAL_VALUE = 9999999999999;

    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'hourly_rates';

    /**
     * Get all user rates for a given project
     *
     * @access public
     * @param  integer  $project_id
     * @return array
     */
    public function getAllByProject($project_id)
    {
        $members = $this->projectUserRoleModel->getAssignableUsers($project_id);

        if (empty($members)) {
            return array();
        }

        return $this->db->table(self::TABLE)->in('user_id', array_keys($members))->desc('date_effective')->findAll();
    }

    /**
     * Get all rates for a given user
     *
     * @access public
     * @param  integer  $user_id  User id
     * @return array
     */
    public function getAllByUser($user_id)
    {
        return $this->db->table(self::TABLE)->eq('user_id', $user_id)->desc('date_effective')->findAll();
    }

    /**
     * Get one hourly rate only when it belongs to the authorised user.
     *
     * @param integer $rate_id
     * @param integer $user_id
     * @return array
     */
    public function getById($rate_id, $user_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $rate_id)->eq('user_id', $user_id)->findOne();
    }

    /**
     * Get current rate for a given user
     *
     * @access public
     * @param  integer  $user_id  User id
     * @return float
     */
    public function getCurrentRate($user_id)
    {
        return (float) ($this->db->table(self::TABLE)->eq('user_id', $user_id)->desc('date_effective')->findOneColumn('rate') ?: 0);
    }

    /**
     * Add a new rate in the database
     *
     * @access public
     * @param integer   $user_id   User id
     * @param float     $rate      Hourly rate
     * @param string    $currency  Currency code
     * @param string    $date      ISO8601 date format
     * @return boolean|integer
     */
    public function create($user_id, $rate, $currency, $date)
    {
        $values = array(
            'user_id' => $user_id,
            'rate' => $this->normalizeFinancialValue($rate),
            'currency' => $currency,
            'date_effective' => $this->dateParser->removeTimeFromTimestamp($this->dateParser->getTimestamp($date)),
        );

        return $this->db->table(self::TABLE)->persist($values);
    }

    /**
     * Remove a specific rate
     *
     * @access public
     * @param  integer    $rate_id
     * @param  integer    $user_id
     * @return boolean
     */
    public function remove($rate_id, $user_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $rate_id)->eq('user_id', $user_id)->remove();
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
            new Validators\Required('user_id', t('Field required')),
            new Validators\Required('rate', t('Field required')),
            new Validators\Numeric('rate', t('This value must be numeric')),
            new Validators\Range('rate', t('This value is outside the supported financial range'), 0, self::MAX_FINANCIAL_VALUE),
            new Validators\Required('date_effective', t('Field required')),
            new Validators\Date('date_effective', t('Invalid date'), $this->dateParser->getParserFormats()),
            new Validators\Required('currency', t('Field required')),
        ));

        $valid = $v->execute();
        $errors = $v->getErrors();

        if ($valid && !array_key_exists($values['currency'], $this->currencyModel->getCurrencies())) {
            $valid = false;
            $errors['currency'][] = t('Invalid currency');
        }

        return array(
            $valid,
            $errors
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
