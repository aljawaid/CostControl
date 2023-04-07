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
                $this->flash->success(t('The currency rate has been added successfully.'));
                $this->response->redirect($this->helper->url->to('CurrencyController', 'show'), true);
                return;
            } else {
                $this->flash->failure(t('Unable to add this currency rate.'));
            }
        }

        $this->create($values, $errors);
    }
}
