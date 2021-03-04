<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Vendeur;
use App\Form\VendeurType;
use App\Repository\VendeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class VendeurController extends AbstractController
{
    /**
     * @Route("/vendeurs", name="vendeurs", methods={"GET"})
     */
    public function index(VendeurRepository $vendeurRepository): Response
    {
        return $this->render('vendeur/index.html.twig', [
            'vendeurs' => $vendeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newvendeur", name="vendeur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vendeur = new Vendeur();
        $form = $this->createForm(VendeurType::class, $vendeur)->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vendeur);
            $entityManager->flush();

            return $this->redirectToRoute('vendeurs');
        }

        return $this->render('vendeur/new.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vendeur/{id}", name="vendeur_show", methods={"GET"})
     */
    public function show(Vendeur $vendeur): Response
    {
        return $this->render('vendeur/show.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }

    /**
     * @Route("/{id}/editvendeur", name="vendeur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vendeur $vendeur): Response
    {
        $form = $this->createForm(VendeurType::class, $vendeur)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vendeurs');
        }

        return $this->render('vendeur/edit.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deldetevendeur", name="vendeur_delete")
     */
    public function delete(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $vendeurRepository = $this->getDoctrine()->getRepository(Vendeur::class);
        $vendeur = $vendeurRepository->find($id);
        $entityManager->remove($vendeur);
        $entityManager->flush();
        return $this->redirectToRoute('vendeurs');
    }
}
