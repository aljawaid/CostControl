<?php

namespace Kanboard\Plugin\CostControl\Helper;

use Kanboard\Core\Base;

class LiveRateHelper extends Base
{
    // https://www.exchangerate-api.com/docs/php-currency-api
    // https://www.exchangerate-api.com/docs/free

    /*****
        RESPONSE JSON EXAMPLE
        {
            "result": "success",
            "provider": "https://www.exchangerate-api.com",
            "documentation": "https://www.exchangerate-api.com/docs/free",
            "terms_of_use": "https://www.exchangerate-api.com/terms",
            "time_last_update_unix": 1680393752,
            "time_last_update_utc": "Sun, 02 Apr 2023 00:02:32 +0000",
            "time_next_update_unix": 1680481582,
            "time_next_update_utc": "Mon, 03 Apr 2023 00:26:22 +0000",
            "time_eol_unix": 0,
            "base_code": "GBP",
            "rates": {
              "GBP": 1,
              "AED": 4.536918,
              "AFN": 107.049854,
              "ALL": 128.90737,
            }
        }
    *****/

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
                    //$base_price = 1; // Your price in USD
                    // $EUR_price = round(($base_price * $response->conversion_rates->EUR), 2);
                    //$live_rate = round(($base_price * $response->conversion_rates->$rate_currency), 2);

                    $base_currency = $application_currency;
                    foreach ($response as $key => $value) {
                        $rate_currency = $key;
                    }
                return $key
                }

            }

            catch(Exception $e) {
            // Handle JSON parse error...
                return 'Error retrieving live rate';
            }
        }
    }
}
