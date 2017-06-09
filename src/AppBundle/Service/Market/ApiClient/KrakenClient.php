<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class KrakenClient
 * @package AppBundle\Service\Market\ApiClient
 */
class KrakenClient implements ApiClientInterface
{
    private $base_uri = "https://api.kraken.com/0/";

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
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair)
    {
        return mb_strtoupper("X" . $pair->getSourceCurrency()->getSymbol() . "Z" . $pair->getTargetCurrency()->getSymbol());
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        return (float) json_decode((string) $this->client->request("GET", "public/Ticker?pair=" . $pair_str)->getBody())->result->{$pair_str}->c[0];
    }
}