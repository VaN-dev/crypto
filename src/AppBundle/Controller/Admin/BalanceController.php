<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Balance;
use AppBundle\Form\BalanceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BalanceController
 * @package AppBundle\Controller
 */
class BalanceController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $balances = $this->getDoctrine()->getRepository("AppBundle:Balance")->findAll();

        return $this->render('AppBundle:Admin\Balance:index.html.twig', [
            'balances' => $balances,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(BalanceType::class, $balance = new Balance());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($balance);
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Balance created.');

            return new RedirectResponse($this->generateUrl('admin.balances'));
        }

        return $this->render('AppBundle:Admin\Balance:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Balance $pair
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Balance $pair)
    {
        $form = $this->createForm(BalanceType::class, $pair);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Balance updated.');

            return new RedirectResponse($this->generateUrl('admin.balances'));
        }

        return $this->render('AppBundle:Admin\Balance:update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Balance $pair
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Balance $pair)
    {
        $this->getDoctrine()->getManager()->remove($pair);
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()->getFlashBag()->add('success', 'Balance deleted.');

        return new RedirectResponse($this->generateUrl('admin.balances'));
    }
}
