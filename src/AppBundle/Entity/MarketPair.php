<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarketPair
 *
 * @ORM\Table(name="market_pair")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarketPairRepository")
 */
class MarketPair
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Market
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Market")
     * @ORM\JoinColumn(name="market_id", nullable=false)
     */
    private $market;

    /**
     * @var Pair
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pair")
     * @ORM\JoinColumn(name="pair_id", nullable=false)
     */
    private $pair;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * @param Market $market
     * @return MarketPair
     */
    public function setMarket($market)
    {
        $this->market = $market;
        return $this;
    }

    /**
     * @return Pair
     */
    public function getPair()
    {
        return $this->pair;
    }

    /**
     * @param Pair $pair
     * @return MarketPair
     */
    public function setPair($pair)
    {
        $this->pair = $pair;
        return $this;
    }


}

