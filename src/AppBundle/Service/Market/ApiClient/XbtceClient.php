<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

/**
 * Class XbtceClient
 * @package AppBundle\Service\Market\ApiClient
 */
class XbtceClient implements ApiClientInterface
{
    /**
     * @var string
     */
    protected $base_uri = "https://cryptottlivewebapi.xbtce.net:8443/api/v1/";

    /**
     * @var string
     */
    protected $key = "";

    /**
     * @var string
     */
    protected $secret = "";

    /**
     * @var int
     */
    protected $noonce;

    /**
     * @var ClientInterface
     */
    protected $client;

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
        return mb_strtoupper($pair->getSourceCurrency()->getSymbol() . $pair->getTargetCurrency()->getSymbol());
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        $headers = [
            'Accept-Encoding' => 'gzip, deflate, sdch, br',
        ];

        return (float) json_decode((string) $this->client->request("GET", "public/ticker/" . $pair_str, ['headers' => $headers])->getBody())[0]->LastBuyPrice;
    }
}