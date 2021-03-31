<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\ReclamationFacc;
use App\Form\ReclamationFaccType;
use App\Repository\ReclamationFaccRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reclamation/facc")
 */
class ReclamationFaccController extends AbstractController
{
    /**
     * @Route("/", name="reclamation_facc_index", methods={"GET"})
     */
    public function index(ReclamationFaccRepository $reclamationFaccRepository): Response
    {
        return $this->render('reclamation_facc/index.html.twig', [
            'reclamation_faccs' => $reclamationFaccRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reclamation_facc_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reclamationFacc = new ReclamationFacc();
        $form = $this->createForm(ReclamationFaccType::class, $reclamationFacc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamationFacc);
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_facc_index');
        }

        return $this->render('reclamation_facc/new.html.twig', [
            'reclamation_facc' => $reclamationFacc,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_facc_show", methods={"GET"})
     */
    public function show(ReclamationFacc $reclamationFacc): Response
    {
        return $this->render('reclamation_facc/show.html.twig', [
            'reclamation_facc' => $reclamationFacc,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reclamation_facc_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReclamationFacc $reclamationFacc): Response
    {
        $form = $this->createForm(ReclamationFaccType::class, $reclamationFacc)
        ->add('modifier',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_facc_index');
        }

        return $this->render('reclamation_facc/edit.html.twig', [
            'reclamation_facc' => $reclamationFacc,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="reclamation_facc_delete")
     */
    public function delete(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamationFaccRepository = $this->getDoctrine()->getRepository(ReclamationFacc::class);
        $reclamationFacc = $reclamationFaccRepository->find($id);
        $entityManager->remove($reclamationFacc);
        $entityManager->flush();
        return $this->redirectToRoute('reclamation_facc_index');
    }
}
