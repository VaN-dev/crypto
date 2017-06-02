<?php

namespace AppBundle\Service\Market\ApiClient;

/**
 * Interface ApiClientInterface
 * @package AppBundle\Service\Market\ApiClient
 */
interface ApiClientInterface
{
    /**
     * @param string $pair
     * @return string
     */
    public function formatPair($pair);

    /**
     * @param string $pair
     * @return float
     */
    public function getTicker($pair);
}