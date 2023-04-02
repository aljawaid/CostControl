<?php

namespace Kanboard\Plugin\CostControl\Helper;

use Kanboard\Core\Base;

class LiveRateHelper extends Base
{
    // https://www.exchangerate-api.com/docs/php-currency-api
    // https://www.exchangerate-api.com/docs/free

    public static function getLiveRate($application_currency, $rate_currency)
    {
        // Fetching JSON
        //$req_url = 'https://open.er-api.com/v6/latest/USD';
        $req_url = 'https://open.er-api.com/v6/latest/'.$application_currency;
        $response_json = file_get_contents($req_url);

        // Continuing if we got a result
        if(false !== $response_json) {
            // Try/catch for json_decode operation
            try {
                // Decoding
                $response = json_decode($response_json);

                // Check for success
                if('success' === $response->result) {

                // YOUR APPLICATION CODE HERE, e.g.
                    $base_price = 1; // Your price in USD
                    // $EUR_price = round(($base_price * $response->conversion_rates->EUR), 2);
                    $live_rate = round(($base_price * $response->conversion_rates->$rate_currency), 2);
                }

                return $live_rate
            }

            catch(Exception $e) {
            // Handle JSON parse error...
                return 'Error retrieving live rate';
            }
        }
    }
}
