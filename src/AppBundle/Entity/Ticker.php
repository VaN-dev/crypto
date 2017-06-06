<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticker
 *
 * @ORM\Table(name="ticker")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TickerRepository")
 */
class Ticker
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=20, scale=10)
     */
    private $value;


    /**
     * Ticker constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Ticker
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set market
     *
     * @param Market $market
     *
     * @return Ticker
     */
    public function setMarket(Market $market)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market
     *
     * @return Market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set pair
     *
     * @param integer $pair
     *
     * @return Ticker
     */
    public function setPair($pair)
    {
        $this->pair = $pair;

        return $this;
    }

    /**
     * Get pair
     *
     * @return Pair
     */
    public function getPair()
    {
        return $this->pair;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Ticker
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

