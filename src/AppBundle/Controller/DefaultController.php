<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        try {
            $tickers = $this->get("app.ticker.manager")->getTickers();
            $balances = $this->get("app.balance.manager")->getBalances();
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'tickers' => $tickers,
            'balances' => $balances,
        ]);
    }
}
