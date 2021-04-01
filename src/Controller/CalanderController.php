<?php

namespace App\Controller;

use App\Entity\Calander;
use App\Entity\Guide;
use App\Form\CalanderType;
use App\Repository\CalanderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/calander")
 */
class CalanderController extends AbstractController
{
    /**
     * @Route("/", name="calander_index", methods={"GET"})
     */
    public function index(CalanderRepository $calanderRepository): Response
    {
        return $this->render('calander/index.html.twig', [
            'calanders' => $calanderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="calander_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $calander = new Calander();
        $form = $this->createForm(CalanderType::class, $calander);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calander);
            $entityManager->flush();

            return $this->redirectToRoute('calander_index');
        }

        return $this->render('calander/new.html.twig', [
            'calander' => $calander,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calander_show", methods={"GET"})
     */
    public function show(Calander $calander): Response
    {
        return $this->render('calander/show.html.twig', [
            'calander' => $calander,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calander_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calander $calander): Response
    {
        $form = $this->createForm(CalanderType::class, $calander);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calander_index');
        }

        return $this->render('calander/edit.html.twig', [
            'calander' => $calander,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calander_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Calander $calander): Response
    {
        if ($this->isCsrfTokenValid('delete' . $calander->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calander);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calander_index');
    }
}
