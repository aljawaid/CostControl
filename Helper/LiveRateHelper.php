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

    Rate Limiting

    We've been providing free versions of our currency conversion API for over 10 years now and have experienced the inevitable DDoS attacks & broken while(1){} loops during this time. As such our open access free exchange rate API has to be rate limited.

    • If you only request once every 24 hours you won't need to read any more of this section. Easy!
    • If you can't keep a cached response for that long, you could still request once every hour and never get rate limited.

    These suggestions are quite reasonable because:

    • The data only refreshes once every 24 hours anyway.
    • Included in the response is the specific time of the next data update.
    • Our Terms permit caching of the data.

    Don't panic if you send too many requests due to a bug and get rate limited. Rate limited IP's will receive HTTP code 429 responses. After 20 minutes the rate limit will finish and new requests will be allowed through.

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
                    foreach ($response->rates as $key => $value) {
                        $rate_currency = $key;
                    }
                return $rate_currency
                }

            }

            catch(Exception $e) {
            // Handle JSON parse error...
                return 'Error retrieving live rate';
            }
        }
    }
}
