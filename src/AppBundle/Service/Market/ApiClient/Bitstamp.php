<?php

namespace AppBundle\Service\Market\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class Bitstamp
 * @package AppBundle\Service\Market\ApiClient
 */
class Bitstamp implements ApiClientInterface
{
    private $base_uri = "https://www.bitstamp.net/api/v2/";

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
        $pair_str = $this->formatPair($pair);

        return json_decode((string) $this->client->request("GET", "ticker/" . $pair_str)->getBody())->last;
    }
}