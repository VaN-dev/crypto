<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GlobalBalance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $markets = $em->getRepository("AppBundle:Market")->findBy(["enabled" => true]);
            $tickers = $this->get("app.ticker.manager")->getTickers();
            $balances = $this->get("app.balance.manager")->getBalances();

            $defaultTickerMarket = $em->getRepository("AppBundle:Market")->findOneBy(["default" => true]);
            $defaultTickerClient = $this->get("app.market.api_client.ticker.collection")->getClient($defaultTickerMarket->getSlug());
            $defaultFiatCurrency = $em->getRepository("AppBundle:Currency")->findOneBy(["default" => true]);

            foreach ($balances as $key => &$balance) {
                $total = 0;
                foreach ($balance["balances"] as $symbol => $value) {
                    $sourceCurrency = $em->getRepository("AppBundle:Currency")->findOneBy(["symbol" => $symbol]);
                    $pair = $em->getRepository("AppBundle:Pair")->findOneBy(["sourceCurrency" => $sourceCurrency, "targetCurrency" => $defaultFiatCurrency]);
                    $balance["balances"][$symbol] = [
                        "value" => (float) $value,
                    ];

                    if (null !== $pair) {
                        $fiat_value = $balance["balances"][$symbol]["value"] * $defaultTickerClient->getTicker($pair);
                        $balance["balances"][$symbol]["fiat_value"] = $fiat_value;
                        $total += $fiat_value;
                    } else {
                        throw new \Exception(sprintf("pair %s/%s not found", $symbol, $defaultFiatCurrency->getSymbol()));
                    }
                }

                $balance["total"] = $total;
            }

            $globalBalance = (new GlobalBalance())
            ->setValue(array_sum( array_map(
                function($balance){
                    return $balance["total"];
                },
                $balances
            )))
            ;

            $em->persist($globalBalance);
            $em->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'markets' => $markets,
            'tickers' => $tickers,
            'balances' => $balances,
            'globalBalance' => $globalBalance,
        ]);
    }
}
