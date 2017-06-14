<?php

namespace AppBundle\Service\Market\ApiClient;

use AppBundle\Entity\Pair;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;

/**
 * Class CoinbaseClient
 * @package AppBundle\Service\Market\ApiClient
 */
class CoinbaseClient implements ApiClientInterface
{
    /**
     * @var string
     */
    protected $base_uri = "https://btc-e.com";

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * BtceClient constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->key = $params["key"];
        $this->secret = $params["secret"];

        $configuration = Configuration::apiKey($this->key, $this->secret);
        $this->client = Client::create($configuration);
    }

    /**
     * @param Pair $pair
     * @return string
     */
    public function formatPair(Pair $pair)
    {
        return mb_strtoupper($pair->getSourceCurrency()->getSymbol() . "-" . $pair->getTargetCurrency()->getSymbol());
    }

    /**
     * @param Pair $pair
     * @return float
     */
    public function getTicker(Pair $pair)
    {
        $pair_str = $this->formatPair($pair);

        return (float) $buyPrice = $this->client->getBuyPrice($pair_str)->getAmount();
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        $output = [];

        $accounts = $this->client->getAccounts();

        foreach ($accounts as $account) {
            if (0 != $account->getBalance()->getAmount()) {
                if (!isset($output[strtolower($account->getCurrency())])) {
                    $output[strtolower($account->getCurrency())] = (float) $account->getBalance()->getAmount();
                } else {
                    $output[strtolower($account->getCurrency())] += $account->getBalance()->getAmount();
                }
            }
        }

        return $output;
    }
}