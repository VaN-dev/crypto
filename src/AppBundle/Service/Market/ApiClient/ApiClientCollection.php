<?php

namespace AppBundle\Service\Market\ApiClient;

/**
 * Class ApiClientCollection
 * @package AppBundle\Service\Market\ApiClient
 */
class ApiClientCollection
{
    /**
     * @var array
     */
    private $clients;

    /**
     * ApiClientCollection constructor.
     */
    public function __construct()
    {
        $this->clients = [];
    }

    /**
     * @param ApiClientInterface $client
     * @param $alias
     */
    public function addClient(ApiClientInterface $client, $alias)
    {
        $this->clients[$alias] = $client;
    }

    /**
     * @param $alias
     * @return mixed
     */
    public function getClient($alias)
    {
        if (array_key_exists($alias, $this->clients)) {
            return $this->clients[$alias];
        }
    }
}