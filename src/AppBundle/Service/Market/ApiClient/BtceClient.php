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

    protected $nonce;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * Bitstamp constructor.
     */
    public function __construct($base_noonce = false)
    {
        $this->client = new Client(["base_uri" => $this->base_uri]);

        if($base_noonce === false) {
            // Try 1?
            $this->noonce = time();
        } else {
            $this->noonce = $base_noonce;
        }
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

//    /**
//     * @return mixed
//     */
//    public function getBalance()
//    {
//        $req['method'] = "getInfo";
//        $mt = $this->getnoonce();
//        $req['nonce'] = $mt[1];
//
//        // generate the POST data string
//        $post_data = http_build_query($req, '', '&');
//
//        // Generate the keyed hash value to post
//        $sign = hash_hmac("sha512", $post_data, $this->secret);
////
////        // Add to the headers
//        $headers = array(
//            'Sign: '.$sign,
//            'Key: '.$this->key,
//        );
//
////
//        $auth = json_decode((string) $this->client->request("POST", "https://btc-e.com/tapi/getInfo", ["headers" => $headers, "body" => $post_data])->getBody());
////
//        dump($auth);
//        die();
//
//        return json_decode((string) $this->client->request("GET", "https://btc-e.com/tapi/getInfo")->getBody());
//    }

    public function getBalance()
    {
        if(!$this->nonce) {
            $nonce = explode(' ', microtime());
            $this->nonce = + $nonce[1].($nonce[0] * 1000000);
            $this->nonce = substr($this->nonce,5);
            $this->nonce = explode(' ', microtime())[1];
        } else {
            $this->nonce ++ ;
        }
        $params['nonce'] = $this->nonce;
        $params['method'] = "getInfo";
        // generate the POST data string
        $post_data = http_build_query($params, '', '&');
        $sign = hash_hmac('sha512', $post_data, $this->secret);
        $headers = [
            'Sign: '.$sign,
            'Key: '.$this->key,
        ];
        static $ch = null;
        if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
        }
        curl_setopt($ch, CURLOPT_URL, 'https://btc-e.com/tapi/');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // run the query
        $res = curl_exec($ch);
        if ($res === false) throw new \Exception('Could not get reply: '.curl_error($ch));
        $dec = json_decode($res, true);
        if (!$dec) throw new \Exception('Invalid data received, please make sure connection is working and requested API exists');
        if($dec['success'] == 0) {
            throw new \Exception($dec['error']);
        }

        // ajout david
        foreach ($dec['return']['funds'] as $k => $fund) {
            if ($fund == 0) {
                unset($dec['return']['funds'][$k]);
            }
        }

        return $dec['return']['funds'];
    }
}