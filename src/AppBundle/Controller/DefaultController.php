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
//        require_once(__DIR__ . '/../../PHP-btce-api-master/btce-api.php');
//        $BTCeAPI = new \BTCeAPI("ISOMSMHE-UJOCU97U-XA0W6KTX-RJSXPEG3-AQS5T1A6","580e0fcd10cebd07851b8256f89a063f28e91aeb04293b63dbd84affd6305d72");
//        $getInfo = $BTCeAPI->apiQuery('getInfo');
        // Print so we can see the output
//        print_r($getInfo);
//        die();

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
