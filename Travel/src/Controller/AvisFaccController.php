<?php

namespace App\Controller;

use App\Entity\AvisFacc;
use App\Form\AvisFaccType;
use App\Repository\AvisFaccRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/avis/facc")
 */
class AvisFaccController extends AbstractController
{
    /**
     * @Route("/", name="avis_facc_index", methods={"GET"})
     */
    public function index(AvisFaccRepository $avisFaccRepository): Response
    {
        return $this->render('avis_facc/index.html.twig', [
            'avis_faccs' => $avisFaccRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="avis_facc_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $avisFacc = new AvisFacc();
        $form = $this->createForm(AvisFaccType::class, $avisFacc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avisFacc);
            $entityManager->flush();

            return $this->redirectToRoute('avis_facc_index');
        }

        return $this->render('avis_facc/new.html.twig', [
            'avis_facc' => $avisFacc,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avis_facc_show", methods={"GET"})
     */
    public function show(AvisFacc $avisFacc): Response
    {
        return $this->render('avis_facc/show.html.twig', [
            'avis_facc' => $avisFacc,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avis_facc_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AvisFacc $avisFacc): Response
    {
        $form = $this->createForm(AvisFaccType::class, $avisFacc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avis_facc_index');
        }

        return $this->render('avis_facc/edit.html.twig', [
            'avis_facc' => $avisFacc,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avis_facc_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AvisFacc $avisFacc): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avisFacc->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avisFacc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avis_facc_index');
    }
}
