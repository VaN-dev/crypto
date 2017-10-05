<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Market
 *
 * @ORM\Table(name="market")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarketRepository")
 */
class Market
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="api_url", type="string", length=255, nullable=true, unique=true)
     */
    private $apiUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="chart_url", type="string", length=255, nullable=true, unique=true)
     */
    private $chartUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $default = false;

    /**
     * @var MarketPair[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MarketPair", mappedBy="market", cascade={"persist", "remove"})
     */
    private $pairs;


    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pairs = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Market
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
     * @param string $slug
     *
     * @return Market
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set apiUrl
     *
     * @param string $apiUrl
     *
     * @return Market
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Get apiUrl
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @return string
     */
    public function getChartUrl()
    {
        return $this->chartUrl;
    }

    /**
     * @param string $chartUrl
     *
     * @return Market
     */
    public function setChartUrl($chartUrl)
    {
        $this->chartUrl = $chartUrl;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Market
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param bool $default
     * @return Market
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Add pair
     *
     * @param MarketPair $pair
     *
     * @return Market
     */
    public function addPair(MarketPair $pair)
    {
        $this->pairs[] = $pair;

        return $this;
    }

    /**
     * Remove pair
     *
     * @param MarketPair $pair
     */
    public function removePair(MarketPair $pair)
    {
        $this->pairs->removeElement($pair);
    }

    /**
     * Get pairs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPairs()
    {
        return $this->pairs;
    }
}
