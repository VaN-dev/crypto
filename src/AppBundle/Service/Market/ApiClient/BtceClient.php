<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

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
    protected $key;

    /**
     * @var string
     */
    protected $secret;

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
    public function __construct($params, $base_noonce = false)
    {
        $this->key = $params["key"];
        $this->secret = $params["secret"];
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

        $response = json_decode((string) $this->client->request("POST", "https://btc-e.com/tapi", $request)->getBody());

        $arr = (array) $response->return->funds;

        foreach ($arr as $k => $fund) {
            if ($fund == 0) {
                unset($arr[$k]);
            }
        }

        return $arr;
    }

//    public function getBalance()
//    {
//        if(!$this->nonce) {
//            $nonce = explode(' ', microtime());
//            $this->nonce = + $nonce[1].($nonce[0] * 1000000);
//            $this->nonce = substr($this->nonce,5);
//            $this->nonce = explode(' ', microtime())[1];
//        } else {
//            $this->nonce ++ ;
//        }
//        $params['nonce'] = $this->nonce;
//        $params['method'] = "getInfo";
//        // generate the POST data string
//        $post_data = http_build_query($params, '', '&');
//        $sign = hash_hmac('sha512', $post_data, $this->secret);
//        $headers = [
//            'Sign: '.$sign,
//            'Key: '.$this->key,
//        ];
//        static $ch = null;
//        if (is_null($ch)) {
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
//        }
//        curl_setopt($ch, CURLOPT_URL, 'https://btc-e.com/tapi/');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        // run the query
//        $res = curl_exec($ch);
//        if ($res === false) throw new \Exception('Could not get reply: '.curl_error($ch));
//        $dec = json_decode($res, true);
//        if (!$dec) throw new \Exception('Invalid data received, please make sure connection is working and requested API exists');
//        if($dec['success'] == 0) {
//            throw new \Exception($dec['error']);
//        }
//
//        // ajout david
//        foreach ($dec['return']['funds'] as $k => $fund) {
//            if ($fund == 0) {
//                unset($dec['return']['funds'][$k]);
//            }
//        }
//
//        return $dec['return']['funds'];
//    }

//    public function getBalance()
//    {
//        $body = ['method' => 'getInfo'];
//        $body['nonce'] = (int)bcmul(bcadd((string)time(), substr(microtime(), 0, 3), 1), '10') - 13e9;
//
//        $postFields = http_build_query($body, '', '&');
//        $request = new Request('POST', 'https://btc-e.com/tapi/', [], $postFields);
//        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
//        $request = $request->withHeader('Sign', hash_hmac('sha512', $postFields, $this->secret));
//        $request = $request->withHeader('Key', $this->key);
//        $response = $this->client->send($request);
//        $data = \GuzzleHttp\json_decode((string)$response->getBody(), true);
//
//        dump($data);
//        die();
//
////        if (self::isSuccessful($response) && isset($data['success']) && 1 === $data['success']) {
////            return $data['return'];
////        } elseif (self::isServerError($response) && $retryNo <= self::RETRY_COUNT) {
////            sleep(self::RETRY_INTERVAL);
////            return $this->sendTradeAPIRequest($request, ++$retryNo);
////        } else {
////            throw new RemoteError(isset($data['error']) ? $data['error'] : null);
////        }
//    }
}