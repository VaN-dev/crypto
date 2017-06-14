<?php

namespace AppBundle\Service\Balance;

use AppBundle\Service\Market\ApiClient\AbstractApiClientCollection;
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
    private $clientsCollection;

    /**
     * BalanceManager constructor.
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
     */
    public function getBalances()
    {
        $output = [];

        $markets = $this->em->getRepository("AppBundle:Market")->findAll();

        foreach ($markets as $market) {
            $client = $this->clientsCollection->getClient($market->getSlug());

            if (null !== $client) {
                $entry = $client->getBalance();

                $output[$market->getName()] = $entry;
            }
        }

        return $output;
    }
}