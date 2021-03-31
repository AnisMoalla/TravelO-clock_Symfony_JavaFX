<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\AvisFacc;
use App\Form\AvisFaccType;
use App\Repository\AvisFaccRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $form = $this->createForm(AvisFaccType::class, $avisFacc)->add('ajouter',SubmitType::class);
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
        $form = $this->createForm(AvisFaccType::class, $avisFacc)
        ->add('modifier',SubmitType::class);
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
     * @Route("/{id}/delete", name="avis_facc_delete")
     */
    public function deleteavis(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $avis_faccRepository = $this->getDoctrine()->getRepository(AvisFacc::class);
        $avis_facc = $avis_faccRepository->find($id);
        $entityManager->remove($avis_facc);
        $entityManager->flush();

        return $this->redirectToRoute('avis_facc_index');
    }
}
