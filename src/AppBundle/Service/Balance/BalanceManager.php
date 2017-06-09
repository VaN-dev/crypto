<?php

namespace AppBundle\Service\Balance;
use AppBundle\Service\Market\ApiClient\BtceClient;
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
     * BalanceManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
                    $client = new BtceClient();
                    break;
                default: $client = null;
                    break;
            }

            if (null !== $client) {
                $entry = $client->getBalance();

                $output[] = $entry;
            }
        }

        dump($output);
        die();

        return $output;
    }
}