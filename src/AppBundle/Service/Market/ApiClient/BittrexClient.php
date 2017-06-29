<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class BittrexClient
 * @package AppBundle\Service\Market\ApiClient
 */
class BittrexClient implements ApiClientInterface
{
    /**
     * @var string
     */
    protected $base_uri = "https://bittrex.com/api/v1.1/";

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
        return (int)bcmul(bcadd((string)time(), substr(microtime(), 0, 3), 1), '10') - 13e9;
    }

    /**
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair)
    {
        return mb_strtoupper($pair->getSourceCurrency()->getSymbol() . "-" . $pair->getTargetCurrency()->getSymbol());
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        return (float) json_decode((string) $this->client->request("GET", "public/getticker?market=" . $pair_str)->getBody())->result->Last;
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

    /**
     * @return mixed
     */
    public function getCurrencies()
    {
        return json_decode((string) $this->client->request("GET", "public/getcurrencies")->getBody())->result;
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getVolume(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        $data = json_decode((string) $this->client->request("GET", "public/getorderbook?market=" . $pair_str . "&type=buy&depth=20")->getBody())->result;


        $volume = (float) array_sum(array_map(function($item) {
            return $item->Quantity;
        }, $data));

        return $volume;
    }
}