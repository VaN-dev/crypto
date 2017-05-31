<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Currency;

/**
 * Class LoadCurrencyData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $currency01 = new Currency();
        $currency01
            ->setName('Euro')
            ->setSymbol('EUR')
            ->setIcon('EUR')
        ;
        $manager->persist($currency01);

        $currency02 = new Currency();
        $currency02
            ->setName('US Dollar')
            ->setSymbol('USD')
            ->setIcon('USD')
        ;
        $manager->persist($currency02);

        $currency03 = new Currency();
        $currency03
            ->setName('Bitcoin')
            ->setSymbol('BTC')
            ->setIcon('BTC')
        ;
        $manager->persist($currency03);

        $currency04 = new Currency();
        $currency04
            ->setName('Ripple')
            ->setSymbol('XRP')
            ->setIcon('XRP')
        ;
        $manager->persist($currency04);

        $currency05 = new Currency();
        $currency05
            ->setName('Ethereum')
            ->setSymbol('ETH')
            ->setIcon('ETH')
        ;
        $manager->persist($currency05);

        $manager->flush();

        $this->addReference('currency-euro', $currency01);
        $this->addReference('currency-dollar', $currency02);
        $this->addReference('currency-bitcoin', $currency03);
        $this->addReference('currency-ripple', $currency04);
        $this->addReference('currency-ethereum', $currency05);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}