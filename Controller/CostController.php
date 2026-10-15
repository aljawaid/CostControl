<?php

namespace Kanboard\Plugin\CostControl\Controller;

use Kanboard\Controller\BaseController;

/**
 * Plugin CostControl
 * Class CostController
 * @author @aljawaid
 * @see tests/security_contract.php
 * @see tests/live_rate_reliability.php
 * @see tests/live_rate_integration.php
 * @see tests/financial_integrity.php
 */

class CostController extends BaseController
{
    /**
     * Display all currency rates
     *
     * @access public
     */
    public function show()
    {
        // Keep the rates page useful when the recommended scheduled command has not run.
        $this->currencyModel->refreshLiveRatesIfDue();

        $this->response->html($this->helper->layout->config('costControl:currency/show', array(
            'application_currency' => $this->configModel->get('application_currency'),
            'rates'                => $this->currencyModel->getAll(),
            'currencies'           => $this->currencyModel->getCurrencies(),
            'title'                => t('Settings') . ' &#10562; ' . t('Currency Rates'),
        )));
    }

    public function showEveryone()
    {
        // Keep the rates page useful when the recommended scheduled command has not run.
        $this->currencyModel->refreshLiveRatesIfDue();

        $this->response->html($this->helper->costControlLayoutHelper->customLayout('costControl:currency/show', array(
            'application_currency' => $this->configModel->get('application_currency'),
            'rates'                => $this->currencyModel->getAll(),
            'currencies'           => $this->currencyModel->getCurrencies(),
            'title'                => t('Cost Control'),
            'user'                 => $this->getUser(),
        )));
    }

    /**
     * Add or change currency rate
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = array(), array $errors = array())
    {
        $this->response->html($this->template->render('costControl:currency/create', array(
            'values'     => $values,
            'errors'     => $errors,
            'currencies' => $this->currencyModel->getCurrencies(),
        )));
    }

    /**
     * Validate and save a new currency rate
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->currencyValidator->validateCreation($values);

        if ($valid && !$this->isSupportedCurrency($values['currency'])) {
            $valid = false;
            $errors['currency'][] = t('Invalid currency');
        }

        if ($valid && !$this->isSupportedRate($values['rate'])) {
            $valid = false;
            $errors['rate'][] = t('Invalid rate');
        }

        if ($valid && (!isset($values['comment']) || !is_string($values['comment']))) {
            $valid = false;
            $errors['comment'][] = t('Invalid comment');
        }

        if ($valid) {
            if ($this->currencyModel->create($values['currency'], $values['rate'], $values['comment'])) {
                $this->flash->success(t('The manual currency rate has been added successfully'));
                $this->response->redirect($this->helper->url->to('CostController', 'showEveryone', array('plugin' => 'CostControl')));
            } else {
                $this->flash->failure(t('Unable to add this manual currency rate'));
            }
        }

        $this->create($values, $errors);
    }

    /**
     * Validate and save a new currency rate
     *
     * @access public
     */
    public function saveWithoutComment()
    {
        $values = $this->request->getValues();
        list($valid, $errors) = $this->currencyValidator->validateCreation($values);

        if ($valid && !$this->isSupportedCurrency($values['currency'])) {
            $valid = false;
            $errors['currency'][] = t('Invalid currency');
        }

        if ($valid && !$this->isSupportedRate($values['rate'])) {
            $valid = false;
            $errors['rate'][] = t('Invalid rate');
        }

        if ($valid) {
            if ($this->currencyModel->createWithoutComment($values['currency'], $values['rate'])) {
                $this->flash->success(t('The manual currency rate has been added successfully'));
                $this->response->redirect($this->helper->url->to('CostController', 'showEveryone', array('plugin' => 'CostControl')));
            } else {
                $this->flash->failure(t('Unable to add this manual currency rate'));
            }
        }

        $this->create($values, $errors);
    }

    /**
     * Change base currency
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function change(array $values = array(), array $errors = array())
    {
        if (empty($values)) {
            $values['application_currency'] = $this->configModel->get('application_currency');
        }

        $this->response->html($this->template->render('currency/change', array(
            'values'     => $values,
            'errors'     => $errors,
            'currencies' => $this->currencyModel->getCurrencies(),
        )));
    }

    /**
     * Save reference currency
     *
     * @access public
     */
    public function update()
    {
        $values = $this->request->getValues();
        $currency = isset($values['application_currency']) ? $values['application_currency'] : '';

        if (!$this->isSupportedCurrency($currency)) {
            $this->flash->failure(t('Unable to save your settings'));
            $this->change($values, array('application_currency' => array(t('Invalid currency'))));
            return;
        }

        if ($this->configModel->save(array('application_currency' => $currency))) {
            $this->flash->success(t('Settings saved successfully'));
        } else {
            $this->flash->failure(t('Unable to save your settings'));
        }

        $this->response->redirect($this->helper->url->to('CostController', 'showEveryone', array('plugin' => 'CostControl')));
    }

    /**
     * EDIT COMMENT
     *
     * @access public
     */
    public function editComment()
    {
        $currency = $this->request->getStringParam('currency');
        $values = $this->currencyModel->getByCurrency($currency);

        if (!$this->isSupportedCurrency($currency) || empty($values)) {
            $this->flash->failure(t('Unable to update this comment'));
            $this->response->redirect($this->helper->url->to('CostController', 'showEveryone', array('plugin' => 'CostControl')));
            return;
        }

        $this->response->html($this->template->render('costControl:cost-control/edit_comment', array(
            'values' => $values,
        )));
    }

    /**
     * EDIT COMMENT FORM
     *
     * @access public
     */
    public function saveCommentForm()
    {
        $values = $this->request->getValues();

        if (
            isset($values['currency'], $values['comment'])
            && is_string($values['comment'])
            && $this->isSupportedCurrency($values['currency'])
            && $this->currencyModel->editComment($values['currency'], $values['comment'])
        ) {
            $this->flash->success(t('Comment updated successfully'));
        } else {
            $this->flash->failure(t('Unable to update this comment'));
        }

        $this->response->redirect($this->helper->url->to('CostController', 'showEveryone', array('plugin' => 'CostControl')));
    }

    /**
     * Accept only currency codes exposed by the current Kanboard currency list.
     *
     * @param string $currency
     * @return boolean
     */
    private function isSupportedCurrency($currency)
    {
        return is_string($currency) && array_key_exists($currency, $this->currencyModel->getCurrencies());
    }

    /**
     * Accept only finite non-negative rates within the fixed-scale database range.
     *
     * @param mixed $rate
     * @return boolean
     */
    private function isSupportedRate($rate)
    {
        return is_numeric($rate)
            && is_finite((float) $rate)
            && (float) $rate >= 0
            && (float) $rate <= \Kanboard\Plugin\CostControl\Model\ExtendedCurrencyModel::MAX_FINANCIAL_VALUE;
    }
}
