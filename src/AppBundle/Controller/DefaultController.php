<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Market;
use AppBundle\Entity\Pair;
use AppBundle\Entity\Ticker;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager();
        $pairs = $em->getRepository("AppBundle:Pair")->findAll();

        $output = [];

        foreach ($pairs as $pair) {
            $entry = [
                'pair' => $pair,
                'data' => [],
            ];

            $market_pairs = $this->getDoctrine()->getRepository("AppBundle:MarketPair")->findBy(["pair" => $pair]);

            foreach ($market_pairs as $market_pair) {

                switch ($market_pair->getMarket()->getSlug()) {
                    case 'bitstamp':
                        $client = $this->get('app.market.api_client.bitstamp');
                        break;
                    case 'kraken':
                        $client = $this->get('app.market.api_client.kraken');
                        break;
                    case 'btc-e':
                        $client = $this->get('app.market.api_client.btce');
                        break;
                    default: $client = null;
                        break;
                }

                if (null === $client) {
                    throw new \Exception('no client found for slug ' . $market_pair->getMarket()->getSlug());
                }

                $value = $client->getTicker($pair);
                $entry['data'][] = [
                    'market' => $market_pair->getMarket(),
                    'pairSlug' => $client->formatPair($pair),
                    'value' => $value,
                ];

                $ticker = new Ticker();
                $ticker
                    ->setMarket($market_pair->getMarket())
                    ->setPair($pair)
                    ->setValue($value)
                ;
                $em->persist($ticker);
                $em->flush();

            }

            $output[] = $entry;
        }

        foreach ($output as &$row) {
            arsort($row["data"]);
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'output' => $output,
        ]);
    }
}
