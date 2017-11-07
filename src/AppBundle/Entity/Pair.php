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
        $slug = "";

        if (null !== $this->sourceCurrency) {
            $slug .= $this->sourceCurrency->getSymbol();
        }
        if (null !== $this->targetCurrency) {
            $slug .= $this->targetCurrency->getSymbol();
        }

        return $slug;
    }

    /**
     * @return bool
     */
    public function isParsable()
    {
        return $this->sourceCurrency->isParsable() || $this->targetCurrency->isParsable();
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
     * @param Currency $sourceCurrency
     *
     * @return Pair
     */
    public function setSourceCurrency(Currency $sourceCurrency)
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
     * @param Currency $targetCurrency
     *
     * @return Pair
     */
    public function setTargetCurrency(Currency $targetCurrency)
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
