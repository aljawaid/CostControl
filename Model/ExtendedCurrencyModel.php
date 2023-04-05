<?php

namespace Kanboard\Plugin\CostControl\Model;

use Kanboard\Core\Base;

/**
 * Currency
 *
 * @package  Kanboard\Model
 * @author   Frederic Guillot
 */
class ExtendedCurrencyModel extends Base
{
    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'currencies';

    /**
     * Get available application currencies
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
            'TRY' => t('TRY - Turkish Lira'),
            'TTD' => t('TTD - Trinidadian Dollar'),
            'TWD' => t('TWD - New Taiwan Dollar'),
            'TZS' => t('TZS - Tanzanian Shilling'),
            'UAH' => t('UAH - Ukrainian Hryvnia'),
            'UGX' => t('UGX - Ugandan Shilling'),
            'USD' => t('USD - United States Dollar'),
            'UYU' => t('UYU - Uruguayan Peso'),
            'UZS' => t('UZS - Uzbekistani Som'),
            'VES' => t('VES - Venezuelan Bolívar'),
            'VND' => t('VND - Vietnamese Dong'),
            'XDR' => t('XDR - International Monetary Fund (IMF) Special Drawing Rights'),
            'YER' => t('YER - Yemeni Rial'),
            'ZAR' => t('ZAR - South African Rand'),
            'ZMW' => t('ZMW - Zambian Kwacha'),
            'ZWL' => t('ZWL - Zimbabwean Dollar'),
        );
    }

    /**
     * Get all currency rates
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
    }

    /**
     * Calculate the price for the reference currency
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
     * Add a new currency rate
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean|integer
     */
    public function create($currency, $rate, $last_modified = time(), $live_rate, $live_rate_updated)
    {
        if ($this->db->table(self::TABLE)->eq('currency', $currency)->exists()) {
            return $this->update($currency, $rate, $last_modified);
        }

        return $this->db->table(self::TABLE)->insert(array('currency' => $currency, 'rate' => $rate, 'last_modified' => $last_modified, 'live_rate' => $live_rate, 'live_rate_updated' => $live_rate_updated));
    }

    /**
     * Update a currency rate
     *
     * @access public
     * @param  string    $currency
     * @param  float     $rate
     * @return boolean
     */
    public function update($currency, $rate, $last_modified = time(), $live_rate, $live_rate_updated)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(array('rate' => $rate, 'last_modified' => $last_modified, 'live_rate' => $live_rate, 'live_rate_updated' => $live_rate_updated));
    }

    public function getLiveRates()
    {
        $req_url = 'https://open.er-api.com/v6/latest/'.$this->configModel->get('application_currency', 'USD');
        $response_json = file_get_contents($req_url);
        $json_currency_rates = json_decode($response_json,true);
        $currencies = $this->getCurrencies();

        foreach ($currencies as $currency => $value) {
            if (isset($json_currency_rates['rates'][$currency])) {
                $live_rate = $json_currency_rates['rates'][$currency];
                $live_rate_updated = $json_currency_rates['time_last_update_unix'];
                $live_rate_next_update = $json_currency_rates['time_next_update_unix'];
                $this->create($currency, $live_rate, $live_rate_updated);
            }
        }
    }

    public function getLiveRatesDates()
    {
        return array($live_rate_updated, $live_rate_next_update);
    }
}
