<?php

namespace Kanboard\Plugin\CostControl\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Plugin\Directory;

/**
 * Plugin CostControl
 * Class CostController
 * @author @aljawaid
 */

class CostController extends \Kanboard\Controller\BaseController
{
    /**
     * Display all currency rates
     *
     * @access public
     */
    public function show()
    {
        $this->response->html($this->helper->layout->config('costControl:currency/show', array(
            'application_currency' => $this->configModel->get('application_currency'),
            'rates'                => $this->currencyModel->getAll(),
            'currencies'           => $this->currencyModel->getCurrencies(),
            'title'                => t('Settings') . ' &#10562; ' . t('Currency Rates'),
        )));
    }

    public function showEveryone()
    {
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

        if ($this->configModel->save($values)) {
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
     * @param array $values
     * @param array $errors
     */
    public function editComment(array $values = array())
    {
        $values = $this->request->getValues();

        $this->response->html($this->template->render('costControl:cost-control/edit_comment', array(
            'values'     => $values,
            'currency'   => $this->request->getStringParam('currency'),
            'comment'    => $this->request->getStringParam('comment'),
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
        //list($valid, $errors) = $this->currencyValidator->validateCreation($values);

        //if ($valid) {
        if ($this->currencyModel->editComment($values)) {
            $this->flash->success(t('Comment updated successfully'));
            $this->response->redirect($this->helper->url->to('CostController', 'showEveryone', array('plugin' => 'CostControl')));
        } else {
            $this->flash->failure(t('Unable to update this comment'));
        }
        //}
    }
}
