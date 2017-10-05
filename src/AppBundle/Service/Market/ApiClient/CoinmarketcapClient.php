<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class CoinmarketcapClient
 * @package AppBundle\Service\Market\ApiClient
 */
class CoinmarketcapClient implements ApiClientInterface
{
    /**
     * @var string
     */
    protected $base_uri = "https://api.coinmarketcap.com/v1/";

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var int
     */
    protected $nonce;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * CoinmarketcapClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client(["base_uri" => $this->base_uri]);
    }

    /**
     * @return int
     */
    protected function getNonce()
    {
        return $nonce=time();
    }

    /**
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair)
    {
        return mb_strtolower(str_replace(" ", "-", $pair->getSourceCurrency()->getName()) . "/" . mb_strtoupper($pair->getTargetCurrency()->getSymbol()));
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        $pair_exploded = explode("/", $pair_str);

//        $t = (string) $this->client->request("GET", "ticker/" . $pair_exploded[0] . "/?convert=" . $pair_exploded[1])->getBody();
//        dump("ticker/" . $pair_exploded[0] . "/?convert=" . $pair_exploded[1]);
////
////        return 0;

        return (float) json_decode((string) $this->client->request("GET", "ticker/" . $pair_exploded[0] . "/?convert=" . $pair_exploded[1])->getBody())[0]->price_usd;
    }
}