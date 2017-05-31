<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $markets = $this->getDoctrine()->getRepository("AppBundle:Market")->findAll();
        $currencies = $this->getDoctrine()->getRepository("AppBundle:Currency")->findAll();

        $output = [];

        foreach ($currencies as $currency) {
            $output[$currency->getName()] = [
                'config' => [
                    'class' => $currency->getSymbol(),
                ],
                'providers' => [],
            ];
        }

//        $providers = [
//            'bitstamp' => [
//                'api_url' => 'https://www.bitstamp.net/api/v2/ticker/',
//                'getter' => 'last',
//                'tickers' => [
//                    'bitcoin' => 'btceur',
//                    'ripple' => 'xrpeur',
//                ],
//            ],
//            'kraken' => [
//                'api_url' => 'https://api.kraken.com/0/public/Ticker?pair=',
//                'getter' => 'result->###ticker###->c[0]',
//                'tickers' => [
//                    'ripple' => 'XXRPZEUR',
//                    'ethereum' => 'XETHZEUR',
//                ],
//            ],
//        ];
//
//        // get coins
//        $coins = [];
//        foreach ($providers as $provider) {
//            foreach ($provider['tickers'] as $ticker => $pair) {
//                if (!in_array($ticker, $coins)) {
//                    array_push($coins, $ticker);
//                }
//            }
//        }
//
//        $data = [];
//
//        foreach ($providers as $provider => $params) {
//            $client = new Client(["base_uri" => $params["api_url"]]);
//
//            foreach ($params['tickers'] as $coin => $ticker) {
//                $value = json_decode((string) $client->request("GET", $params["api_url"] . $ticker)->getBody());
//                $getter = str_replace("###ticker###", $ticker, $params["getter"]);
////                dump($value);
//                dump($getter);
//                $data[$coin][$provider] = $value->$getter;
//            }
//
//        }
//
//        dump($data);
//        die();

        $bistamp = new Client(["base_uri" => "https://www.bitstamp.net/api/v2/"]);
        $kraken = new Client(["base_uri" => "https://api.kraken.com/0/public/"]);
        $btce = new Client(["base_uri" => "https://btc-e.com/api/3/"]);
//        $poloniex = new Client(["base_uri" => "https://poloniex.com/public/"]);

//        dump(json_decode((string) $poloniex->request("GET", "?command=returnTicker")->getBody()));
//        die();

//        dump(json_decode((string) $kraken->request("GET", "Assets")->getBody()));
//        dump(json_decode((string) $kraken->request("GET", "Ticker?pair=XXRPZEUR")->getBody())->result->XXRPZEUR->c[0]);
//        die();

        $data = [
            'bitcoin' => [
                'config' => [
                    'class' => 'BTC',
                ],
                'providers' => [
                    'bitstamp' => json_decode((string) $bistamp->request("GET", "ticker/btceur")->getBody())->last,
                    'btc-e' => json_decode((string) $btce->request("GET", "ticker/btc_eur")->getBody())->btc_eur->last,
                ],
            ],
            'ripple' => [
                'config' => [
                    'class' => 'XRP',
                ],
                'providers' => [
                    'bitstamp' => json_decode((string) $bistamp->request("GET", "ticker/xrpeur")->getBody())->last,
                    'kraken' => json_decode((string) $kraken->request("GET", "Ticker?pair=XXRPZEUR")->getBody())->result->XXRPZEUR->c[0],
                ],
            ],
            'ethereum' => [
                'config' => [
                    'class' => 'ETH',
                ],
                'providers' => [
                    'kraken' => json_decode((string) $kraken->request("GET", "Ticker?pair=XETHZEUR")->getBody())->result->XETHZEUR->c[0],
                    'btc-e' => json_decode((string) $btce->request("GET", "ticker/eth_eur")->getBody())->eth_eur->last,
                ],
            ],
        ];

        foreach ($data as $coin_name => &$coin_data) {
            asort($coin_data['providers']);
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'data' => $data,
        ]);
    }
}
