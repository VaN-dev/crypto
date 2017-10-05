<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Payward\KrakenAPI;

/**
 * Class KrakenClient
 * @package AppBundle\Service\Market\ApiClient
 */
class KrakenClient implements ApiClientInterface
{
    private $_mapping = [
        "BTC" => "XXBT",
    ];

    /**
     * @var string
     */
    private $base_uri = "https://api.kraken.com/0/";

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var KrakenAPI
     */
    private $client2;

    /**
     * KrakenClient constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->key = $params["key"];
        $this->secret = $params["secret"];

        $this->client = new Client(["base_uri" => $this->base_uri]);

        $beta = false;
        $url = $beta ? 'https://api.beta.kraken.com' : 'https://api.kraken.com';
        $sslverify = $beta ? false : true;
        $version = 0;
        $this->client2 = new KrakenAPI($this->key, $this->secret, $url, $version, $sslverify);
    }

    /**
     * @return int
     */
    protected function getNonce()
    {
        $nonce = explode(' ', microtime());
        return $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');
    }

    /**
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair)
    {
        if (in_array($pair->getSourceCurrency()->getSymbol(), $this->_mapping)) {
            $source_symbol = $this->_mapping[$pair->getSourceCurrency()->getSymbol()];
        } else {
            $source_symbol = "X" . $pair->getSourceCurrency()->getSymbol();
        }

        return mb_strtoupper($source_symbol . "Z" . $pair->getTargetCurrency()->getSymbol());
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        $response = $this->client2->QueryPublic('Ticker', ["pair" => $pair_str]);

        $result = $response["result"][$pair_str]["c"][0];

        return $result;
    }

    public function getBalance()
    {
        $response = $this->client2->QueryPrivate('Balance');

        foreach ($response["result"] as $k => $result) {
            if (false !== $mapped_key = array_search($k, $this->_mapping)) {
                $response["result"][$mapped_key] = $result;
                unset($response["result"][$k]);
            }
        }

        return $response["result"];
    }
}