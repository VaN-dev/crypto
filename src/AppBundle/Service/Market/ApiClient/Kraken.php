<?php

namespace AppBundle\Service\Market\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class Kraken
 * @package AppBundle\Service\Market\ApiClient
 */
class Kraken implements ApiClientInterface
{
    private $base_uri = "https://api.kraken.com/0/public/";

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Bitstamp constructor.
     */
    public function __construct()
    {
        $this->client = new Client(["base_uri" => $this->base_uri]);
    }

    /**
     * @param string $pair
     * @return string
     */
    public function formatPair($pair)
    {
        return $pair;
    }

    /**
     * @param string $pair
     * @return float
     */
    public function getTicker($pair)
    {
        return json_decode((string) $this->client->request("GET", "Ticker?pair=" . $pair)->getBody())->result->{$pair}->c[0];
    }
}