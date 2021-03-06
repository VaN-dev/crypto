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
            ->setParsable(false)
            ->setDefault(true)
        ;
        $manager->persist($currency01);

        $currency02 = new Currency();
        $currency02
            ->setName('US Dollar')
            ->setSymbol('USD')
            ->setIcon('USD')
            ->setParsable(false)
        ;
        $manager->persist($currency02);

        $currency03 = new Currency();
        $currency03
            ->setName('Bitcoin')
            ->setSymbol('BTC')
            ->setIcon('BTC')
            ->setParsable(true)
        ;
        $manager->persist($currency03);

        $currency04 = new Currency();
        $currency04
            ->setName('Ripple')
            ->setSymbol('XRP')
            ->setIcon('XRP')
            ->setParsable(true)
        ;
        $manager->persist($currency04);

        $currency05 = new Currency();
        $currency05
            ->setName('Ethereum')
            ->setSymbol('ETH')
            ->setIcon('ETH')
            ->setParsable(true)
        ;
        $manager->persist($currency05);

        $currency06 = new Currency();
        $currency06
            ->setName('Litecoin')
            ->setSymbol('LTC')
            ->setIcon('LTC')
            ->setParsable(true)
        ;
        $manager->persist($currency06);

        $currency07 = new Currency();
        $currency07
            ->setName('Bitcoin Cash')
            ->setSymbol('BCH')
            ->setIcon('BCH')
            ->setParsable(true)
        ;
        $manager->persist($currency07);

        $currency08 = new Currency();
        $currency08
            ->setName('Monetha')
            ->setSymbol('MTH')
            ->setIcon('MTH')
            ->setParsable(true)
        ;
        $manager->persist($currency08);

        $currency09 = new Currency();
        $currency09
            ->setName('Stellar')
            ->setSymbol('XLM')
            ->setIcon('XLM')
            ->setParsable(true)
        ;
        $manager->persist($currency09);

        $manager->flush();

        $this->addReference('currency-euro', $currency01);
        $this->addReference('currency-dollar', $currency02);
        $this->addReference('currency-bitcoin', $currency03);
        $this->addReference('currency-ripple', $currency04);
        $this->addReference('currency-ethereum', $currency05);
        $this->addReference('currency-litecoin', $currency06);
        $this->addReference('currency-bitcoincash', $currency07);
        $this->addReference('currency-monetha', $currency08);
        $this->addReference('currency-stellar', $currency09);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}