<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Pair;
use AppBundle\Form\PairType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PairController
 * @package AppBundle\Controller
 */
class PairController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $pairs = $this->getDoctrine()->getRepository("AppBundle:Pair")->findAll();

        return $this->render('AppBundle:Admin\Pair:index.html.twig', [
            'pairs' => $pairs,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(PairType::class, $pair = new Pair());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($pair);
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Pair created.');

            return new RedirectResponse($this->generateUrl('admin.pairs'));
        }

        return $this->render('AppBundle:Admin\Pair:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Pair $pair
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Pair $pair)
    {
        $form = $this->createForm(PairType::class, $pair);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'Pair updated.');

            return new RedirectResponse($this->generateUrl('admin.pairs'));
        }

        return $this->render('AppBundle:Admin\Pair:update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Pair $pair
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Pair $pair)
    {
        $this->getDoctrine()->getManager()->remove($pair);
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()->getFlashBag()->add('success', 'Pair deleted.');

        return new RedirectResponse($this->generateUrl('admin.pairs'));
    }
}
