<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class BtceClient
 * @package AppBundle\Service\Market\ApiClient
 */
class BtceClient implements ApiClientInterface
{
    /**
     * @var string
     */
    protected $base_uri = "https://btc-e.com";

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
     * BtceClient constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->key = $params["key"];
        $this->secret = $params["secret"];

        $this->client = new Client(["base_uri" => $this->base_uri]);
    }

    /**
     * @return int
     */
    protected function getNonce()
    {
        $nonce = (int) bcmul(bcadd((string)time(), substr(microtime(), 0, 3), 1), '10') - 13e9;

        return $nonce;
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

        return (float) json_decode((string) $this->client->request("GET", "/api/3/ticker/" . $pair_str)->getBody())->{$pair_str}->last;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        $body["method"] = "getInfo";
        $body["nonce"] = $this->getNonce();

        // generate the POST data string
        $post_data = http_build_query($body, '', '&');

        $headers = [
            'Sign' => hash_hmac("sha512", $post_data, $this->secret),
            'Key' => $this->key,
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ];

        $request = [
            "headers" => $headers,
            "body" => $post_data,
        ];

        $response = json_decode((string) $this->client->request("POST", "/tapi", $request)->getBody());

        $output = (array) $response->return->funds;

        foreach ($output as $k => $fund) {
            if ($fund == 0) {
                unset($output[$k]);
            }
        }

        return $output;
    }
}