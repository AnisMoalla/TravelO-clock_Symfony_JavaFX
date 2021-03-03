<?php

namespace App\Controller;

use App\Entity\Facceuil;
use App\Form\FacceuilType;
use App\Repository\FacceuilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facceuil")
 */
class FacceuilController extends AbstractController
{
    /**
     * @Route("/", name="facceuil_index", methods={"GET"})
     */
    public function index(FacceuilRepository $facceuilRepository): Response
    {
        return $this->render('facceuil/index.html.twig', [
            'facceuils' => $facceuilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="facceuil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $facceuil = new Facceuil();
        $form = $this->createForm(FacceuilType::class, $facceuil)->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facceuil);
            $entityManager->flush();

            return $this->redirectToRoute('facceuil_index');
        }

        return $this->render('facceuil/new.html.twig', [
            'facceuil' => $facceuil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="facceuil_show", methods={"GET"})
     */
    public function show(Facceuil $facceuil): Response
    {
        return $this->render('facceuil/show.html.twig', [
            'facceuil' => $facceuil,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="facceuil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Facceuil $facceuil): Response
    {
        $form = $this->createForm(FacceuilType::class, $facceuil)->add('modifier',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facceuil_index');
        }

        return $this->render('facceuil/edit.html.twig', [
            'facceuil' => $facceuil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="facceuil_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Facceuil $facceuil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facceuil->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($facceuil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facceuil_index');
    }
}
