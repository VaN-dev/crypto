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
    protected $base_uri = "https://btc-e.com/api/3/";

    /**
     * @var string
     */
    protected $key = "ISOMSMHE-UJOCU97U-XA0W6KTX-RJSXPEG3-AQS5T1A6";

    /**
     * @var string
     */
    protected $secret = "580e0fcd10cebd07851b8256f89a063f28e91aeb04293b63dbd84affd6305d72";

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
     * @return array
     */
    protected function getnoonce() {
        $this->noonce++;
        return array(0.05, $this->noonce);
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

    /**
     * @return mixed
     */
    public function getBalance()
    {
        $req['method'] = "GET";
        $mt = $this->getnoonce();
        $req['nonce'] = $mt[1];

        // generate the POST data string
        $post_data = http_build_query($req, '', '&');

        // Generate the keyed hash value to post
        $sign = hash_hmac("sha512", $post_data, $this->secret);

        // Add to the headers
        $headers = array(
            'Sign: '.$sign,
            'Key: '.$this->key,
        );

        $auth = json_decode((string) $this->client->request("POST", "https://btc-e.com/tapi/", ["headers" => $headers, "body" => $post_data])->getBody());

        dump($auth);
        die();

        return json_decode((string) $this->client->request("GET", "getInfo")->getBody());
    }
}