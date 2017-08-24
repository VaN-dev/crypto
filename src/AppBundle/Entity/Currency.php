<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Currency
 *
 * @ORM\Table(name="currency", uniqueConstraints={@UniqueConstraint(name="currency_unique", columns={"name", "symbol"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 */
class Currency
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=255)
     */
    private $symbol;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @var bool
     *
     * @ORM\Column(name="parsable", type="boolean")
     */
    private $parsable;


    /**
     * CUSTOM METHODS
     */

    /**
     * @return string
     */

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
     * Set name
     *
     * @param string $name
     *
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     *
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Currency
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return boolean
     */
    public function isParsable()
    {
        return $this->parsable;
    }

    /**
     * @param boolean $parsable
     *
     * @return Currency
     */
    public function setParsable($parsable)
    {
        $this->parsable = $parsable;

        return $this;
    }



    /**
     * Get parsable
     *
     * @return boolean
     */
    public function getParsable()
    {
        return $this->parsable;
    }
}
