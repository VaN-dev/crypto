<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Pair;

/**
 * Class LoadPairData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadPairData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $pair01 = new Pair();
        $pair01
            ->setSourceCurrency($this->getReference('currency-bitcoin'))
            ->setTargetCurrency($this->getReference('currency-euro'))
        ;
        $manager->persist($pair01);

        $pair02 = new Pair();
        $pair02
            ->setSourceCurrency($this->getReference('currency-ripple'))
            ->setTargetCurrency($this->getReference('currency-euro'))
        ;
        $manager->persist($pair02);

        $pair03 = new Pair();
        $pair03
            ->setSourceCurrency($this->getReference('currency-ethereum'))
            ->setTargetCurrency($this->getReference('currency-euro'))
        ;
        $manager->persist($pair03);

        $pair04 = new Pair();
        $pair04
            ->setSourceCurrency($this->getReference('currency-litecoin'))
            ->setTargetCurrency($this->getReference('currency-euro'))
        ;
        $manager->persist($pair04);

        $pair05 = new Pair();
        $pair05
            ->setSourceCurrency($this->getReference('currency-bitcoin'))
            ->setTargetCurrency($this->getReference('currency-ripple'))
        ;
        $manager->persist($pair05);

        $pair06 = new Pair();
        $pair06
            ->setSourceCurrency($this->getReference('currency-bitcoincash'))
            ->setTargetCurrency($this->getReference('currency-euro'))
        ;
        $manager->persist($pair06);

        $pair07 = new Pair();
        $pair07
            ->setSourceCurrency($this->getReference('currency-stellar'))
            ->setTargetCurrency($this->getReference('currency-euro'))
        ;
        $manager->persist($pair07);

        $manager->flush();

        $this->addReference('pair-btceur', $pair01);
        $this->addReference('pair-xrpeur', $pair02);
        $this->addReference('pair-etheur', $pair03);
        $this->addReference('pair-ltceur', $pair04);
        $this->addReference('pair-xrpbtc', $pair05);
        $this->addReference('pair-bcheur', $pair06);
        $this->addReference('pair-xlmeur', $pair07);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 10;
    }
}