<?php

namespace AppBundle\Service\Market\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class Btce
 * @package AppBundle\Service\Market\ApiClient
 */
class Btce implements ApiClientInterface
{
    private $base_uri = "https://btc-e.com/api/3/";

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
        return json_decode((string) $this->client->request("GET", "ticker/" . $pair)->getBody())->{$pair}->last;
    }
}