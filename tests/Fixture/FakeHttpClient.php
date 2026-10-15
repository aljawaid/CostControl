<?php

namespace Kanboard\Plugin\CostControl\Tests\Fixture;

/**
 * Provide deterministic provider responses to tests without network access.
 *
 * Related test: tests/live_rate_integration.php.
 * Related production file: Model/ExtendedCurrencyModel.php.
 */
class FakeHttpClient
{
    private $payload = array();


    private $exception;


    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    public function setException($exception)
    {
        $this->exception = $exception;
    }

    public function getJson($url, array $headers = array(), $raiseForErrors = false, $followRedirects = true)
    {
        if ($this->exception !== null) {
            throw $this->exception;
        }

        return $this->payload;
    }
}
