<?php

namespace AppBundle\Service\Balance;

use AppBundle\Service\Market\ApiClient\ApiClientCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BalanceManager
 * @package AppBundle\Service\Ticker
 */
class BalanceManager
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
     * BalanceManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, ApiClientCollection $collection)
    {
        $this->em = $em;
        $this->clients_collection = $collection;
    }

    /**
     * @return array
     */
    public function getBalances()
    {
        $output = [];

        $markets = $this->em->getRepository("AppBundle:Market")->findAll();

        foreach ($markets as $market) {
            switch ($market->getSlug()) {
                case 'btc-e':
                    $client = $this->clients_collection->getClient('btc-e');
                    break;
                default: $client = null;
                    break;
            }

            if (null !== $client) {
                $entry = $client->getBalance();

                $output[$market->getName()] = $entry;
            }
        }

        return $output;
    }
}