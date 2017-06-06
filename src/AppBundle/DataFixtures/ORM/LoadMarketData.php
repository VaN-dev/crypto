<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Market;

/**
 * Class LoadMarketData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadMarketData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $market01 = new Market();
        $market01
            ->setName('Bitstamp')
            ->setSlug('bitstamp')
            ->setApiUrl('https://www.bitstamp.net/api/v2/')
        ;
        $manager->persist($market01);

        $market02 = new Market();
        $market02
            ->setName('Kraken')
            ->setSlug('kraken')
            ->setApiUrl('https://api.kraken.com/0/public/')
        ;
        $manager->persist($market02);

        $market03 = new Market();
        $market03
            ->setName('BTC-e')
            ->setSlug('btc-e')
            ->setApiUrl('https://btc-e.com/api/3/')
            ->setChartUrl('https://btc-e.com/exchange/')
        ;
        $manager->persist($market03);

        $manager->flush();

        $this->addReference('market-bitstamp', $market01);
        $this->addReference('market-kraken', $market02);
        $this->addReference('market-btce', $market03);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}