<?php

namespace AppBundle\Service\Ticker;

use AppBundle\Entity\Ticker;
use AppBundle\Service\Market\ApiClient\ApiClientCollection;
use AppBundle\Service\Market\ApiClient\BitstampClient;
use AppBundle\Service\Market\ApiClient\KrakenClient;
use AppBundle\Service\Market\ApiClient\XbtceClient;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TickerManager
 * @package AppBundle\Service\Ticker
 */
class TickerManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var array
     */
    private $clients_collection;

    /**
     * TickerManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiClientCollection $collection
     */
    public function __construct(EntityManagerInterface $em, ApiClientCollection $collection)
    {
        $this->em = $em;
        $this->clients_collection = $collection;

    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTickers()
    {
        $pairs = $this->em->getRepository("AppBundle:Pair")->findAll();

        $output = [];

        foreach ($pairs as $pair) {
            $entry = [
                'pair' => $pair,
                'data' => [],
            ];

            $market_pairs = $this->em->getRepository("AppBundle:MarketPair")->findBy(["pair" => $pair]);

            foreach ($market_pairs as $market_pair) {

                switch ($market_pair->getMarket()->getSlug()) {
                    case 'bitstamp':
                        $client = new BitstampClient();
                        break;
                    case 'kraken':
                        $client = new KrakenClient();
                        break;
                    case 'btc-e':
                        $client = $this->clients_collection->getClient('btc-e');
                        break;
                    case 'xbtce':
                        $client = new XbtceClient();
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
                $this->em->persist($ticker);
                $this->em->flush();

            }

            $output[] = $entry;
        }

        foreach ($output as &$row) {
            arsort($row["data"]);
        }

        return $output;
    }
}