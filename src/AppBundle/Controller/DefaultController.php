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
        $currencies = $this->getDoctrine()->getRepository("AppBundle:Currency")->findBy(["parsable" => true]);

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

        $bitsamp = $this->get('app.market.api_client.bitstamp');
        $btce = $this->get('app.market.api_client.btce');
        $kraken = $this->get('app.market.api_client.kraken');

        $data = [
            'bitcoin' => [
                'config' => [
                    'class' => 'BTC',
                ],
                'providers' => [
                    'bitstamp' => $bitsamp->getTicker("btceur"),
                    'btc-e' => $btce->getTicker("btc_eur"),
                ],
            ],
            'ripple' => [
                'config' => [
                    'class' => 'XRP',
                ],
                'providers' => [
                    'bitstamp' => $bitsamp->getTicker("xrpeur"),
                    'kraken' => $kraken->getTicker("XXRPZEUR"),
                ],
            ],
            'ethereum' => [
                'config' => [
                    'class' => 'ETH',
                ],
                'providers' => [
                    'kraken' => $kraken->getTicker("XETHZEUR"),
                    'btc-e' => $btce->getTicker("eth_eur"),
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
