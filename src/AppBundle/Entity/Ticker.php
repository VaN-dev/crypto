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
     * @var int
     *
     * @ORM\Column(name="market_id", type="integer")
     */
    private $marketId;

    /**
     * @var int
     *
     * @ORM\Column(name="source_currency_id", type="integer")
     */
    private $sourceCurrencyId;

    /**
     * @var int
     *
     * @ORM\Column(name="target_currency_id", type="integer")
     */
    private $targetCurrencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=20, scale=10)
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
     * Set marketId
     *
     * @param integer $marketId
     *
     * @return Ticker
     */
    public function setMarketId($marketId)
    {
        $this->marketId = $marketId;

        return $this;
    }

    /**
     * Get marketId
     *
     * @return int
     */
    public function getMarketId()
    {
        return $this->marketId;
    }

    /**
     * Set sourceCurrencyId
     *
     * @param integer $sourceCurrencyId
     *
     * @return Ticker
     */
    public function setSourceCurrencyId($sourceCurrencyId)
    {
        $this->sourceCurrencyId = $sourceCurrencyId;

        return $this;
    }

    /**
     * Get sourceCurrencyId
     *
     * @return int
     */
    public function getSourceCurrencyId()
    {
        return $this->sourceCurrencyId;
    }

    /**
     * Set targetCurrencyId
     *
     * @param integer $targetCurrencyId
     *
     * @return Ticker
     */
    public function setTargetCurrencyId($targetCurrencyId)
    {
        $this->targetCurrencyId = $targetCurrencyId;

        return $this;
    }

    /**
     * Get targetCurrencyId
     *
     * @return int
     */
    public function getTargetCurrencyId()
    {
        return $this->targetCurrencyId;
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

