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

        $market04 = new Market();
        $market04
            ->setName('Xbtce')
            ->setSlug('xbtce')
            ->setApiUrl('https://cryptottlivewebapi.xbtce.net:8443/api/v1/')
        ;
        $manager->persist($market04);

        $market05 = new Market();
        $market05
            ->setName('Coinbase')
            ->setSlug('coinbase')
        ;
        $manager->persist($market05);

        $market06 = new Market();
        $market06
            ->setName('Bittrex')
            ->setSlug('bittrex')
        ;
        $manager->persist($market06);

        $manager->flush();

        $this->addReference('market-bitstamp', $market01);
        $this->addReference('market-kraken', $market02);
        $this->addReference('market-btce', $market03);
        $this->addReference('market-xbtce', $market04);
        $this->addReference('market-coinbase', $market05);
        $this->addReference('market-bittrex', $market06);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}