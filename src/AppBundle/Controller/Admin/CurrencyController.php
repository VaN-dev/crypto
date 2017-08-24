<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Currency;
use AppBundle\Form\CurrencyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CurrencyController
 * @package AppBundle\Controller
 */
class CurrencyController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $currencies = $this->getDoctrine()->getRepository("AppBundle:Currency")->findAll();

        return $this->render('AppBundle:Admin\Currency:index.html.twig', [
            'currencies' => $currencies,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CurrencyType::class, $currency = new Currency());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($currency);
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Currency created.');

            return new RedirectResponse($this->generateUrl('admin.currencies'));
        }

        return $this->render('AppBundle:Admin\Currency:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Currency $currency
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Currency $currency)
    {
        $form = $this->createForm(CurrencyType::class, $currency);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Currency updated.');

            return new RedirectResponse($this->generateUrl('admin.currencies'));
        }

        return $this->render('AppBundle:Admin\Currency:update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Currency $currency
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Currency $currency)
    {
        $this->getDoctrine()->getManager()->remove($currency);
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()->getFlashBag()->add('success', 'Currency deleted.');

        return new RedirectResponse($this->generateUrl('admin.currencies'));
    }
}
