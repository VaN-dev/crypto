<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\MarketPair;

/**
 * Class LoadMarketPairData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadMarketPairData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $marketPair01 = new MarketPair();
        $marketPair01
            ->setMarket($this->getReference('market-bitstamp'))
            ->setPair($this->getReference('pair-btceur'))

        ;
        $manager->persist($marketPair01);

        $marketPair02 = new MarketPair();
        $marketPair02
            ->setMarket($this->getReference('market-btce'))
            ->setPair($this->getReference('pair-btceur'))

        ;
        $manager->persist($marketPair02);

        $marketPair03 = new MarketPair();
        $marketPair03
            ->setMarket($this->getReference('market-bitstamp'))
            ->setPair($this->getReference('pair-xrpeur'))

        ;
        $manager->persist($marketPair03);

        $marketPair04 = new MarketPair();
        $marketPair04
            ->setMarket($this->getReference('market-kraken'))
            ->setPair($this->getReference('pair-xrpeur'))

        ;
        $manager->persist($marketPair04);

        $marketPair05 = new MarketPair();
        $marketPair05
            ->setMarket($this->getReference('market-kraken'))
            ->setPair($this->getReference('pair-etheur'))

        ;
        $manager->persist($marketPair05);

        $marketPair06 = new MarketPair();
        $marketPair06
            ->setMarket($this->getReference('market-btce'))
            ->setPair($this->getReference('pair-etheur'))

        ;
        $manager->persist($marketPair06);

        $marketPair07 = new MarketPair();
        $marketPair07
            ->setMarket($this->getReference('market-btce'))
            ->setPair($this->getReference('pair-ltceur'))

        ;
        $manager->persist($marketPair07);

        $marketPair08 = new MarketPair();
        $marketPair08
            ->setMarket($this->getReference('market-kraken'))
            ->setPair($this->getReference('pair-ltceur'))

        ;
        $manager->persist($marketPair08);

        $marketPair09 = new MarketPair();
        $marketPair09
            ->setMarket($this->getReference('market-xbtce'))
            ->setPair($this->getReference('pair-btceur'))

        ;
        $manager->persist($marketPair09);

        $manager->flush();


    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 20;
    }
}