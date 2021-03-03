<?php

namespace App\Controller;

use App\Entity\PostFrum;
use App\Form\PostFrumType;
use App\Repository\PostFrumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post/frum")
 */
class PostFrumController extends AbstractController
{
    /**
     * @Route("/", name="post_frum_index", methods={"GET"})
     */
    public function index(PostFrumRepository $postFrumRepository): Response
    {
        return $this->render('post_frum/index.html.twig', [
            'post_frums' => $postFrumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_frum_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $postFrum = new PostFrum();
        $form = $this->createForm(PostFrumType::class, $postFrum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($postFrum);
            $entityManager->flush();

            return $this->redirectToRoute('post_frum_index');
        }

        return $this->render('post_frum/new.html.twig', [
            'post_frum' => $postFrum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_frum_show", methods={"GET"})
     */
    public function show(PostFrum $postFrum): Response
    {
        return $this->render('post_frum/show.html.twig', [
            'post_frum' => $postFrum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_frum_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PostFrum $postFrum): Response
    {
        $form = $this->createForm(PostFrumType::class, $postFrum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_frum_index');
        }

        return $this->render('post_frum/edit.html.twig', [
            'post_frum' => $postFrum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_frum_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PostFrum $postFrum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postFrum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($postFrum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_frum_index');
    }
}
