<?php

namespace AppBundle\Controller;

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
        try {
            $markets = $this->getDoctrine()->getRepository("AppBundle:Market")->findBy(["enabled" => true]);
            $tickers = $this->get("app.ticker.manager")->getTickers();
            $balances = $this->get("app.balance.manager")->getBalances();
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'markets' => $markets,
            'tickers' => $tickers,
            'balances' => $balances,
        ]);
    }
}
