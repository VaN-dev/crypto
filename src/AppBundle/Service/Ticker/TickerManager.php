<?php

namespace AppBundle\Service\Ticker;

use AppBundle\Entity\Ticker;
use AppBundle\Service\Market\ApiClient\AbstractApiClientCollection;
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
    private $clientsCollection;

    /**
     * TickerManager constructor.
     * @param EntityManagerInterface $em
     * @param AbstractApiClientCollection $collection
     */
    public function __construct(EntityManagerInterface $em, AbstractApiClientCollection $collection)
    {
        $this->em = $em;
        $this->clientsCollection = $collection;

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
                $client = $this->clientsCollection->getClient($market_pair->getMarket()->getSlug());

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