<?php

namespace App\Controller;

use App\Entity\PostForum;

use App\Form\PostForumType;
use App\Repository\PostForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/post/forum")
 */
class PostForumController extends AbstractController
{
    /**
     * @Route("/", name="post_forum_index", methods={"GET"})
     */
    public function index(PostForumRepository $postForumRepository): Response
    {
        return $this->render('post_forum/index.html.twig', [
            'post_forums' => $postForumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_forum_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $postForum = new PostForum();
        $form = $this->createForm(PostForumType::class, $postForum)->add('save',submitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($postForum);
            $entityManager->flush();

            return $this->redirectToRoute('post_forum_index');
        }

        return $this->render('post_forum/new.html.twig', [
            'post_forum' => $postForum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_forum_show", methods={"GET"})
     */
    public function show(PostForum $postForum): Response
    {
        return $this->render('post_forum/show.html.twig', [
            'post_forum' => $postForum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_forum_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PostForum $postForum): Response
    {
        $form = $this->createForm(PostForumType::class, $postForum)->add('Modifier',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_forum_index');
        }

        return $this->render('post_forum/edit.html.twig', [
            'post_forum' => $postForum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletepostforum", name="post_forum_delete")
     */
    public function delete(Request $request, $id): Response
    {
      
            $entityManager = $this->getDoctrine()->getManager();
            $PostForumRepository = $this->getDoctrine()->getRepository(PostForum::class);
            $postForum= $PostForumRepository->find($id);
            $entityManager->remove($postForum);
            $entityManager->flush();
        

        return $this->redirectToRoute('post_forum_index');
    }





 


}
