<?php

namespace AppBundle\Service\Market\ApiClient;
use AppBundle\Entity\Pair;

/**
 * Interface ApiClientInterface
 * @package AppBundle\Service\Market\ApiClient
 */
interface ApiClientInterface
{
    /**
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair);

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair);
}