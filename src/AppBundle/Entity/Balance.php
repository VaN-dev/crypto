<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Balance
 *
 * @ORM\Table(name="balance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BalanceRepository")
 */
class Balance
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
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Currency")
     */
    private $currency;

    /**
     * @var Market
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Market")
     */
    private $market;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=16, scale=8)
     */
    private $value;


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
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return Balance
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
        return $this;
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
     * @return Balance
     */
    public function setMarket(Market $market)
    {
        $this->market = $market;
        return $this;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Balance
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

