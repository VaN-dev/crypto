<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Market;
use AppBundle\Entity\Pair;
use AppBundle\Form\AutomatedTradeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MarketController
 * @package AppBundle\Controller
 * @Route("/market")
 */
class MarketController extends Controller
{
    /**
     * @Route("/{market}", name="market.show")
     */
    public function showAction(Request $request, Market $market)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $this->get('app.market.api_client.volume.collection')->getClient($market->getSlug());
        $volumes = [];

        $marketPairs = $em->getRepository("AppBundle:MarketPair")->findBy(["market" => $market]);
        foreach ($marketPairs as $marketPair) {
            $volumes[$marketPair->getPair()->getSlug()] = $client->getVolume($marketPair->getPair());
        }

        if ($request->getSession()->has('volumes')) {
            $latestVolumes = $request->getSession()->get('volumes');
        } else {
            $latestVolumes = [];
        }

        foreach ($volumes as $pair => $volume) {
            $latestVolumes[$pair][] = [
                "volume" => $volume,
                "change" => $volume * 100 / end($latestVolumes[$pair])["volume"],
            ];
        }

        foreach ($latestVolumes as $pair => &$latestVolume) {
            if (count($latestVolume) > 5) {
                array_shift($latestVolume);
            }
        }

        $request->getSession()->set('volumes', $latestVolumes);

        $trade = [];
        $form = $this->createForm(AutomatedTradeType::class, $trade);

        return $this->render('@App/Market/show.html.twig', [
            'market' => $market,
            'volumes' => $latestVolumes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{market}/import-currencies", name="market.import.currencies")
     */
    public function importCurrenciesAction(Request $request, Market $market)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $this->get('app.market.api_client.ticker.collection')->getClient($market->getSlug());

        $clientCurrencies = $client->getCurrencies();

        $dbCurrencies = $em->getRepository("AppBundle:Currency")->getFlatSymbols();

        $count = 0;
        foreach ($clientCurrencies as $k => $clientCurrency) {
            if (!in_array($clientCurrency->Currency, $dbCurrencies)) {
                $currency = new Currency();
                $currency
                    ->setName($clientCurrency->CurrencyLong)
                    ->setSymbol($clientCurrency->Currency)
                    ->setParsable(true)
                ;

                $em->persist($currency);
                $count++;
            }
        }

        $em->flush();

        $request->getSession()->getFlashBag()->add('success', sprintf('Succesfully imported %d currencies.', $count));

        return new RedirectResponse($this->generateUrl("market.show", ["market" => $market->getId()]));
    }
}
