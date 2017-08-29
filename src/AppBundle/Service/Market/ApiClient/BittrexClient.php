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
        return $nonce=time();
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
        $nonce = $this->getNonce();

        $uri='https://bittrex.com/api/v1.1/account/getbalances?apikey='.$this->key.'&nonce='.$nonce;
        $sign = hash_hmac('sha512', $uri, $this->secret);

        $headers = [
            "apisign" => $sign,
        ];

        $body = [
            "apikey" => $this->key,
            "nonce" => $this->getNonce(),
        ];

        $request = [
            "headers" => $headers,
        ];

        $response = json_decode((string) $this->client->request("GET", "account/getbalances?apikey=" . $this->key . '&nonce=' . $nonce, $request)->getBody())->result;

        $balances = [];
        foreach ($response as $balance) {
            $balances[$balance->Currency] = $balance->Balance;
        }



//        $apikey=$this->key;
//        $apisecret=$this->secret;
//
//        $nonce=time();
//        $uri='https://bittrex.com/api/v1.1/account/getbalances?apikey='.$apikey.'&nonce='.$nonce;
//        $sign=hash_hmac('sha512',$uri,$apisecret);
//        $ch = curl_init($uri);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
//        $execResult = curl_exec($ch);
//        $obj = json_decode($execResult);
//
//        dump($obj);

        return $balances;
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

        if (is_array($data)) {
            $volume = (float) array_sum(array_map(function($item) {
                return $item->Quantity;
            }, $data));
        } else {
            $volume = null;
        }

        return $volume;
    }
}