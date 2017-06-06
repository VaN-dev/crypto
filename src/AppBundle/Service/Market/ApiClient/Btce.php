<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
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
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair)
    {
        return mb_strtolower($pair->getSourceCurrency()->getSymbol() . "_" . $pair->getTargetCurrency()->getSymbol());
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        return (float) json_decode((string) $this->client->request("GET", "ticker/" . $pair_str)->getBody())->{$pair_str}->last;
    }
}