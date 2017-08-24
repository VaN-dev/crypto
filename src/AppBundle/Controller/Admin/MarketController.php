<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Market;
use AppBundle\Entity\MarketPair;
use AppBundle\Entity\Pair;
use AppBundle\Form\MarketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MarketController
 * @package AppBundle\Controller
 */
class MarketController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $markets = $this->getDoctrine()->getRepository("AppBundle:Market")->findAll();

        return $this->render('AppBundle:Admin\Market:index.html.twig', [
            'markets' => $markets,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(MarketType::class, $market = new Market());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($request->request->get('market')['pairs'] as $pair) {
                $marketPair = new MarketPair();
                $marketPair
                    ->setMarket($market)
                    ->setPair($this->getDoctrine()->getRepository("AppBundle:Pair")->find($pair))
                ;

                $this->getDoctrine()->getManager()->persist($marketPair);
            }

            $this->getDoctrine()->getManager()->persist($market);
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Market created.');

            return new RedirectResponse($this->generateUrl('admin.markets'));
        }

        return $this->render('AppBundle:Admin\Market:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Market $market
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Market $market)
    {
        $form = $this->createForm(MarketType::class, $market);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $posted = [];
            $original = $this->getDoctrine()->getRepository("AppBundle:Pair")->fetchPairsByMarket($market);

            foreach ($request->request->get('market')['pairs'] as $pair) {
                $posted[] = $this->getDoctrine()->getRepository("AppBundle:Pair")->find($pair);
            }

            $to_add = array_udiff($posted, $original, function ($a, $b) {
                return strcmp(spl_object_hash($a), spl_object_hash($b));
            });

            $to_remove = array_udiff($original, $posted, function ($a, $b) {
                return strcmp(spl_object_hash($a), spl_object_hash($b));
            });

            foreach ($to_add as $pair) {
                $marketPair = new MarketPair();
                $marketPair
                    ->setMarket($market)
                    ->setPair($pair)
                ;

                $this->getDoctrine()->getManager()->persist($marketPair);
            }

            foreach ($to_remove as $pair) {
                $marketPair = $this->getDoctrine()->getRepository("AppBundle:MarketPair")->findOneBy(["market" => $market, "pair" => $pair]);
                $this->getDoctrine()->getManager()->remove($marketPair);
            }

            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Market updated.');

            return new RedirectResponse($this->generateUrl('admin.markets'));
        }

        return $this->render('AppBundle:Admin\Market:update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Market $market
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Market $market)
    {
        $this->getDoctrine()->getManager()->remove($market);
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()->getFlashBag()->add('success', 'Market deleted.');

        return new RedirectResponse($this->generateUrl('admin.markets'));
    }
}
