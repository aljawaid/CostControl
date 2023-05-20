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
 */
class ExtendedCurrencyModel extends Base
{
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
     * GET JSON & UPDATE CURRENCY LIVE RATES
     *
     * @access public
     *
     */
    public function getLiveRates()
    {
        //error_log('ABOUT TO CHECK JSON FOR LIVE RATES',0);
        $req_url = 'https://open.er-api.com/v6/latest/' . $this->configModel->get('application_currency', 'USD');
        $response_json = file_get_contents($req_url);
        $json_currency_rates = json_decode($response_json, true);
        $currencies = $this->getCurrencies();
        $live_rate_updated = $json_currency_rates['time_last_update_unix'];
        //error_log('LIVE RATES RESPONSE: OK '.$live_rate_updated,0);
        $live_rate_next_update = $json_currency_rates['time_next_update_unix'];
        $this->configModel->save(['cost_control_last_updated' => $live_rate_updated]);
        $this->configModel->save(['cost_control_next_update' => $live_rate_next_update]);

        foreach ($currencies as $currency => $value) {
            if (isset($json_currency_rates['rates'][$currency])) {
                $live_rate = $json_currency_rates['rates'][$currency];
                if ($this->getLiveRate($currency) != $live_rate) {
                    $this->createWithLive($currency, $live_rate, time());
                }
            }
        }
    }

    /**
     * EDIT COMMENT
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean|integer
     */
    public function editComment($values)
    {
        if ($this->db->table(self::TABLE)->eq('currency', $values['currency'])->exists()) {
            return $this->updateComment($values['currency'], $values['comment']);
        }

        return $this->db->table(self::TABLE)->insert(array('currency' => $values['currency'], 'comment' => $values['comment']));
    }

    /**
     * UPDATE COMMENT
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean
     */
    public function updateComment($currency, $comment)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(array('comment' => $comment));
    }
}
