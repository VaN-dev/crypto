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

        // api balances
        $markets = $this->em->getRepository("AppBundle:Market")->findBy(["enabled" => true]);

        foreach ($markets as $market) {
            $client = $this->clientsCollection->getClient($market->getSlug());

            try {

                if (null !== $client) {
                    $entry = [
                        "market" => $market,
                        "balances" => $client->getBalance(),
//                        "value" => $this->em->getRepository("AppBundle:Ticker")->findOneBy(["market" => $market], ["createdAt" => "DESC"]),
                    ];

                    $output[$market->getName()] = $entry;
                }

            } catch (\Exception $e) {
                dump($e->getMessage());
                die();
            }
        }

        // local balances
        $localBalances = $this->em->getRepository("AppBundle:Balance")->findAll();

        foreach ($localBalances as $localBalance) {
            if (isset($output[$localBalance->getMarket()->getName()])) {
                if (isset($output[$localBalance->getMarket()->getName()]["balances"][$localBalance->getCurrency()->getSymbol()])) {
                    $output[$localBalance->getMarket()->getName()]["balances"][$localBalance->getCurrency()->getSymbol()] += $localBalance->getValue();
                } else {
                    $output[$localBalance->getMarket()->getName()]["balances"][$localBalance->getCurrency()->getSymbol()] = $localBalance->getValue();
                }
            } else {
                $output[$localBalance->getMarket()->getName()] = [
                    "market" => $localBalance->getMarket(),
                    "balances" => [
                        $localBalance->getCurrency()->getSymbol() => $localBalance->getValue(),
                    ],
                ];
            }
        }

        return $output;
    }
}