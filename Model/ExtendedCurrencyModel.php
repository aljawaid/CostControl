<?php

namespace Kanboard\Plugin\CostControl\Model;

use Kanboard\Core\Base;

/**
 * Currency
 *
 * @package  Kanboard\Model
 * @author   Frederic Guillot
 * @author   aljawaid
 * @author   Craig Crosby
 * @see tests/security_contract.php
 * @see tests/live_rate_reliability.php
 * @see tests/live_rate_integration.php
 */
class ExtendedCurrencyModel extends Base
{
    public const LIVE_RATE_API_URL = 'https://open.er-api.com/v6/latest/';
    public const LIVE_RATE_UPDATE_GRACE = 60;
    public const LIVE_RATE_FAILURE_RETRY = 900;

    /**
     * SQL TABLE NAME
     *
     * @var string
     */
    const TABLE = 'currencies';

    /**
     * GET AVAILABLE CURRENCIES AVAILABLE IN THE APPLICATION
     *
     * @access public
     * @return array
     */
    public function getCurrencies()
    {
        return array(
            'AED' => t('AED - Emirati Dirham'),
            'AFN' => t('AFN - Afghan Afghani'),
            'ALL' => t('ALL - Albanian Lek'),
            'AMD' => t('AMD - Armenian Dram'),
            'ANG' => t('ANG - Dutch Guilder'),
            'AOA' => t('AOA - Angolan Kwanza'),
            'ARS' => t('ARS - Argentine Peso'),
            'AUD' => t('AUD - Australian Dollar'),
            'AZN' => t('AZN - Azerbaijani Manat'),
            'BAM' => t('BAM - Bosnian Convertible Mark'),
            'BBD' => t('BBD - Barbadian Dollar'),
            'BDT' => t('BDT - Bangladeshi Taka'),
            'BGN' => t('BGN - Bulgarian Lev'),
            'BHD' => t('BHD - Bahraini Dinar'),
            'BRL' => t('BRL - Brazilian Real'),
            'BSD' => t('BSD - Bahamian Dollar'),
            'BWP' => t('BWP - Botswanan Pula'),
            'BYN' => t('BYN - Belarusian Ruble'),
            'BZD' => t('BZD - Belizean Dollar'),
            'CAD' => t('CAD - Canadian Dollar'),
            'CHF' => t('CHF - Swiss Franc'),
            'CLP' => t('CLP - Chilean Peso'),
            'CNY' => t('CNY - Chinese Yuan'),
            'COP' => t('COP - Colombian Peso'),
            'CUP' => t('CUP - Cuban Peso'),
            'CZK' => t('CZK - Czech Koruna'),
            'DJF' => t('DJF - Djiboutian Franc'),
            'DKK' => t('DKK - Danish Krone'),
            'DOP' => t('DOP - Dominican Peso'),
            'DZD' => t('DZD - Algerian Dinar'),
            'EGP' => t('EGP - Egyptian Pound'),
            'EUR' => t('EUR - European Union'),
            'FJD' => t('FJD - Fijian Dollar'),
            'GBP' => t('GBP - British Pound Sterling'),
            'GEL' => t('GEL - Georgian Lari'),
            'GGP' => t('GGP - Guernsey Pound'),
            'GHS' => t('GHS - Ghanaian Cedi'),
            'GIP' => t('GIP - Gibraltar Pound'),
            'GMD' => t('GMD - Gambian Dalasi'),
            'HKD' => t('HKD - Hong Kong Dollar'),
            'HNL' => t('HNL - Honduran Lempira'),
            'HRK' => t('HRK - Croatian Kuna'),
            'HTG' => t('HTG - Haitian Gourde'),
            'HUF' => t('HUF - Hungarian Forint'),
            'IDR' => t('IDR - Indonesian Rupiah'),
            'ILS' => t('ILS - Israeli Shekel'),
            'IMP' => t('IMP - Isle of Man Pound'),
            'INR' => t('INR - Indian Rupee'),
            'IQD' => t('IQD - Iraqi Dinar'),
            'IRR' => t('IRR - Iranian Rial'),
            'ISK' => t('ISK - Icelandic Króna'),
            'JEP' => t('JEP - Jersey Pound'),
            'JMD' => t('JMD - Jamaican Dollar'),
            'JOD' => t('JOD - Jordanian Dinar'),
            'JPY' => t('JPY - Japanese Yen'),
            'KES' => t('KES - Kenyan Shilling'),
            'KHR' => t('KHR - Cambodian Riel'),
            'KRW' => t('KRW - South Korean Won'),
            'KWD' => t('KWD - Kuwaiti Dinar'),
            'KYD' => t('KYD - Cayman Islands Dollar'),
            'KZT' => t('KZT - Kazakhstani Tenge'),
            'LBP' => t('LBP - Lebanese Pound'),
            'LKR' => t('LKR - Sri Lankan Rupee'),
            'LRD' => t('LRD - Liberian Dollar'),
            'LYD' => t('LYD - Libyan Dinar'),
            'MAD' => t('MAD - Moroccan Dirham'),
            'MDL' => t('MDL - Moldovan Leu'),
            'MGA' => t('MGA - Malagasy Ariary'),
            'MKD' => t('MKD - Macedonian Denar'),
            'MMK' => t('MMK - Myanmar Kyat'),
            'MNT' => t('MNT - Mongolian Tögrög'),
            'MOP' => t('MOP - Macanese Pataca'),
            'MRU' => t('MRU - Mauritanian Ouguiya'),
            'MUR' => t('MUR - Mauritian Rupee'),
            'MVR' => t('MVR - Maldivian Rufiyaa'),
            'MWK' => t('MWK - Malawian Kwacha'),
            'MXN' => t('MXN - Mexican Peso'),
            'MYR' => t('MYR - Malaysia Ringgit'),
            'MZN' => t('MZN - Mozambican Metical'),
            'NAD' => t('NAD - Namibian Dollar'),
            'NGN' => t('NGN - Nigerian Naira'),
            'NOK' => t('NOK - Norwegian Krone'),
            'NPR' => t('NPR - Nepalese Rupee'),
            'NZD' => t('NZD - New Zealand Dollar'),
            'OMR' => t('OMR - Omani Rial'),
            'PAB' => t('PAB - Panamanian Balboa'),
            'PEN' => t('PEN - Peruvian Sol'),
            'PGK' => t('PGK - Papua New Guinean Kina'),
            'PHP' => t('PHP - Philippine Peso'),
            'PKR' => t('PKR - Pakistani Rupee'),
            'PLN' => t('PLN - Polish Zloty'),
            'PYG' => t('PYG - Paraguayan Guarani'),
            'QAR' => t('QAR - Qatari Riyal'),
            'RON' => t('RON - Romanian Leu'),
            'RSD' => t('RSD - Serbian Dinar'),
            'RUB' => t('RUB - Russian Ruble'),
            'RWF' => t('RWF - Rwandan Franc'),
            'SAR' => t('SAR - Saudi Riyal'),
            'SCR' => t('SCR - Seychellois Rupee'),
            'SDG' => t('SDG - Sudanese Pound'),
            'SEK' => t('SEK - Swedish Krona'),
            'SGD' => t('SGD - Singapore Dollar'),
            'SLL' => t('SLL - Sierra Leonean Leone'),
            'SOS' => t('SOS - Somali Shilling'),
            'SYP' => t('SYP - Syrian Pound'),
            'THB' => t('THB - Thai Baht'),
            'TJS' => t('TJS - Tajikistani Somoni'),
            'TMT' => t('TMT - Turkmenistani Manat'),
            'TND' => t('TND - Tunisian Dinar'),
            'TRY' => t('TRY - Turkish Lira [TRY/TRL]'),
            'TTD' => t('TTD - Trinidadian Dollar'),
            'TWD' => t('TWD - New Taiwan Dollar'),
            'TZS' => t('TZS - Tanzanian Shilling'),
            'UAH' => t('UAH - Ukrainian Hryvnia'),
            'UGX' => t('UGX - Ugandan Shilling'),
            'USD' => t('USD - United States Dollar'),
            'UYU' => t('UYU - Uruguayan Peso'),
            'UZS' => t('UZS - Uzbekistani Som'),
            'VES' => t('VBL - Venezuelan Bolívar'),
            'VND' => t('VND - Vietnamese Dong'),
            'XDR' => t('XDR - IMF Special Drawing Rights [XDR/SDR]'),
            'XBT' => t('XBT - Bitcoin [XBT/BTC]'),
            'YER' => t('YER - Yemeni Rial'),
            'ZAR' => t('ZAR - South African Rand'),
            'ZMW' => t('ZMW - Zambian Kwacha'),
            'ZWL' => t('ZWL - Zimbabwean Dollar'),
        );
    }

    /**
     * GET ALL CURRENCY RATES
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
    }

    /**
     * Get one persisted currency record.
     *
     * @param string $currency
     * @return array
     */
    public function getByCurrency($currency)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->findOne();
    }

    /**
     * GET CURRENCY LIVE RATE
     *
     * @access public
     * @return array
     */
    public function getLiveRate($currency)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->findOneColumn('live_rate');
    }

    /**
     * GET REFERENCE CURRENCY RATES
     *
     * @access public
     * @return array
     */
    public function getReferenceCurrency()
    {
        return $this->db->table(self::TABLE)->eq('currency', $this->configModel->get('cost_control_reference_currency'))->findOne();
    }

    /**
     * Calculate the price for the base currency
     *
     * @access public
     * @param  string  $currency
     * @param  double  $price
     * @return double
     */
    public function getPrice($currency, $price)
    {
        static $rates = null;
        $reference = $this->configModel->get('application_currency', 'USD');

        if ($reference !== $currency) {
            $rates = $rates === null ? $this->db->hashtable(self::TABLE)->getAll('currency', 'rate') : $rates;
            $rate = isset($rates[$currency]) ? $rates[$currency] : 1;

            return $rate * $price;
        }

        return $price;
    }

    /**
     * ADD NEW CURRENCY RATE WITH LIVE RATE
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean|integer
     */
    public function createWithLive($currency, $live_rate, $live_rate_updated)
    {
        if ($this->db->table(self::TABLE)->eq('currency', $currency)->exists()) {
            return $this->updateWithLive($currency, $live_rate, $live_rate_updated);
        }

        return $this->db->table(self::TABLE)->insert(array('currency' => $currency, 'live_rate' => $live_rate, 'live_rate_updated' => $live_rate_updated));
    }

    /**
     * ADD NEW CURRENCY RATE
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean|integer
     */
    public function create($currency, $rate, $comment)
    {
        if ($this->db->table(self::TABLE)->eq('currency', $currency)->exists()) {
            return $this->update($currency, $rate, $comment);
        }

        return $this->db->table(self::TABLE)->insert(array('currency' => $currency, 'rate' => $rate, 'last_modified' => time(), 'comment' => $comment));
    }

    /**
     * ADD NEW CURRENCY RATE WITHOUT COMMENT
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean|integer
     */
    public function createWithoutComment($currency, $rate)
    {
        if ($this->db->table(self::TABLE)->eq('currency', $currency)->exists()) {
            return $this->updateWithoutComment($currency, $rate);
        }

        return $this->db->table(self::TABLE)->insert(array('currency' => $currency, 'rate' => $rate, 'last_modified' => time()));
    }

    /**
     * UPDATE CURRENCY RATE
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean
     */
    public function update($currency, $rate, $comment)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(array('rate' => $rate, 'last_modified' => time(), 'comment' => $comment));
    }

    /**
     * UPDATE CURRENCY RATE WITHOUT COMMENT
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean
     */
    public function updateWithoutComment($currency, $rate)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(array('rate' => $rate, 'last_modified' => time()));
    }

    /**
     * UPDATE CURRENCY LIVE RATE
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean
     */
    public function updateWithLive($currency, $live_rate, $live_rate_updated)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(array('live_rate' => $live_rate, 'live_rate_updated' => $live_rate_updated));
    }

    /**
     * Check whether another provider request is due.
     *
     * @param integer|null $now
     * @return boolean
     */
    public function isLiveRateRefreshDue($now = null)
    {
        $now = $now === null ? time() : (int) $now;
        $nextAttempt = (int) $this->configModel->getOption('cost_control_next_live_rate_attempt', 0);

        if ($nextAttempt > $now) {
            return false;
        }

        $nextUpdate = (int) $this->configModel->getOption('cost_control_next_update', 0);

        return $nextUpdate === 0 || $now >= $nextUpdate + self::LIVE_RATE_UPDATE_GRACE;
    }

    /**
     * Refresh live rates only when the source data is due.
     *
     * @return boolean
     */
    public function refreshLiveRatesIfDue()
    {
        return !$this->isLiveRateRefreshDue() || $this->getLiveRates();
    }

    /**
     * Retrieve validated live rates and preserve the last good data on failure.
     *
     * @return boolean
     */
    public function getLiveRates()
    {
        $checkedAt = time();
        $baseCurrency = $this->configModel->get('application_currency', 'USD');

        if (!array_key_exists($baseCurrency, $this->getCurrencies())) {
            $this->logger->warning('CostControl: live-rate refresh skipped because the base currency is invalid.');
            return false;
        }

        // Reserve a short retry window before the request to prevent bursts from concurrent page loads.
        if (! $this->configModel->save(
            array(
                'cost_control_last_checked_live_rates' => $checkedAt,
                'cost_control_next_live_rate_attempt' => $checkedAt + self::LIVE_RATE_FAILURE_RETRY,
            )
        )) {
            $this->logger->warning('CostControl: unable to record the live-rate refresh attempt.');
            return false;
        }

        try {
            $payload = $this->httpClient->getJson(self::LIVE_RATE_API_URL . rawurlencode($baseCurrency), array(), true, false);
        } catch (\Throwable $exception) {
            $this->logger->warning('CostControl: live-rate provider request failed: ' . $exception->getMessage());
            return false;
        }

        if (!$this->isValidLiveRatePayload($payload, $baseCurrency)) {
            $this->logger->warning('CostControl: live-rate provider returned an invalid response.');
            return false;
        }

        $updatedAt = (int) $payload['time_last_update_unix'];
        $nextUpdate = (int) $payload['time_next_update_unix'];

        if (!$this->storeLiveRates($payload['rates'], $updatedAt)) {
            $this->logger->warning('CostControl: unable to store the validated live-rate response.');
            return false;
        }

        if (! $this->configModel->save(
            array(
                'cost_control_last_updated' => $updatedAt,
                'cost_control_next_update' => $nextUpdate,
                'cost_control_next_live_rate_attempt' => $nextUpdate + self::LIVE_RATE_UPDATE_GRACE,
            )
        )) {
            $this->logger->warning('CostControl: live rates were stored but their update metadata could not be saved.');
            return false;
        }

        return true;
    }

    /**
     * Validate the provider response before any rate is written.
     *
     * @param array  $payload
     * @param string $baseCurrency
     * @return boolean
     */
    private function isValidLiveRatePayload(array $payload, $baseCurrency)
    {
        if (
            !isset($payload['result'], $payload['base_code'], $payload['time_last_update_unix'], $payload['time_next_update_unix'], $payload['rates'])
            || $payload['result'] !== 'success'
            || $payload['base_code'] !== $baseCurrency
            || !is_numeric($payload['time_last_update_unix'])
            || !is_numeric($payload['time_next_update_unix'])
            || !is_array($payload['rates'])
            || !isset($payload['rates'][$baseCurrency])
            || (float) $payload['rates'][$baseCurrency] !== 1.0
            || (int) $payload['time_last_update_unix'] <= 0
            || (int) $payload['time_next_update_unix'] <= (int) $payload['time_last_update_unix']
        ) {
            return false;
        }

        foreach ($payload['rates'] as $currency => $rate) {
            if (!is_string($currency) || !is_numeric($rate) || !is_finite((float) $rate) || (float) $rate <= 0) {
                return false;
            }
        }

        foreach ($this->getCurrencies() as $currency => $label) {
            // ExchangeRate-API does not publish the non-ISO Bitcoin alias retained by CostControl.
            if ($currency !== 'XBT' && !isset($payload['rates'][$currency])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Store supported rates as one database unit.
     *
     * @param array   $rates
     * @param integer $updatedAt
     * @return boolean
     */
    private function storeLiveRates(array $rates, $updatedAt)
    {
        $this->db->startTransaction();

        foreach ($this->getCurrencies() as $currency => $label) {
            if (isset($rates[$currency]) && !$this->createWithLive($currency, (float) $rates[$currency], $updatedAt)) {
                $this->db->cancelTransaction();
                return false;
            }
        }

        $this->db->closeTransaction();

        return true;
    }

    /**
     * EDIT COMMENT
     *
     * @access public
     * @param  string $currency
     * @param  string $comment
     * @return boolean
     */
    public function editComment($currency, $comment)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(array('comment' => $comment));
    }
}
