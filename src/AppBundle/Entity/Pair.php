<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pair
 *
 * @ORM\Table(name="pair")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PairRepository")
 */
class Pair
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
     * @ORM\JoinColumn(name="source_currency_id", nullable=false)
     */
    private $sourceCurrency;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Currency")
     * @ORM\JoinColumn(name="target_currency_id", nullable=false)
     */
    private $targetCurrency;


    /**
     * CUSTOM METHODS
     */

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->sourceCurrency->getSymbol() . $this->targetCurrency->getSymbol();
    }

    /**
     * GETTERS & SETTERS
     */

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
     * Set sourceCurrency
     *
     * @param integer $sourceCurrency
     *
     * @return Pair
     */
    public function setSourceCurrency($sourceCurrency)
    {
        $this->sourceCurrency = $sourceCurrency;

        return $this;
    }

    /**
     * Get sourceCurrencyId
     *
     * @return Currency
     */
    public function getSourceCurrency()
    {
        return $this->sourceCurrency;
    }

    /**
     * Set targetCurrency
     *
     * @param integer $targetCurrency
     *
     * @return Pair
     */
    public function setTargetCurrency($targetCurrency)
    {
        $this->targetCurrency = $targetCurrency;

        return $this;
    }

    /**
     * Get targetCurrency
     *
     * @return Currency
     */
    public function getTargetCurrency()
    {
        return $this->targetCurrency;
    }
}

