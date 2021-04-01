<?php

namespace App\Controller;


use App\Entity\Commentaire;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PostForumRepository;
use App\Repository\CategoryRepository;
use App\Entity\PostForum;
use App\Entity\Category;
use App\Entity\Postlike;
use App\Entity\User;
use App\Repository\CommentaireRepository;
use App\Repository\PostlikeRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/forum/category")
 */
class FrontForumController extends AbstractController
{
    /**
     * @Route("/", name="front_forum")
     */

    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('Forum/Forum.html.twig', [
            'Categories' => $categoryRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}", name="showCategory", methods={"GET"})
     */
    public function show($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $PostForumRepository = $this->getDoctrine()->getRepository(PostForum::class);
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $posts_form = $PostForumRepository->PostBycategory($id);

        return $this->render('Forum/listPost.html.twig', [
            'posts' => $posts_form, 'category' => $categoryRepository->find($id)
        ]);
    }


    /**
     * @Route("/show/{id}/showNewPost",name="ShowNewPostFront")
     */

    public function ShowNewPost(Category $category): Response
    {
        return $this->render("Forum/AjoutePost.html.twig", ['category' => $category]);
    }

    /**
     * @Route("/show/newPost", name="newPostFront")
     */
    public function newPost(Request $request): Response
    {
        $post = new PostForum();
        $post->setName($request->get('title'));
        $post->setContent($request->get('content'));
        $post->setUser($this->getUser());
        $CategoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $CategoryRepository->find($request->get('category'));

        $post->setCategory($category);
        $post->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();


        return $this->json(['code' => 200, 'message' => "post Ajouter"], 200);
    }


    /**
     * @Route("/show/{id}", name="postFront")
     */
    public function showFront(PostForumRepository $PostForumRepository, $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $PostForumRepository->find($id);


        return $this->render('Forum/post.html.twig', [
            'post' => $post,

        ]);
    }

    /**
     * @Route("/show/{id}/comment",name="newComment")
     *
     */
    public function addcomment(Request $request, PostForum $post, CommentaireRepository $commentRep, \Swift_Mailer $mailer): Response
    {
        $comment = new Commentaire();
        $comment->setPostF($post);

        $comment->setContent($request->request->get('content'));
        $comment->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($comment);

        $entityManager->flush();

        $message = (new \Swift_Message('soneone commented'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($comment->getPostF()->getUser()->getEmail())
            ->setBody("somme one comment in your post");

        $mailer->send($message);

        return $this->json(['code' => 200, 'message' => $comment->getContent()], 200);
    }

    /**
     * @Route("/show/{id}/listcomment",name="listComment",methods={"GET"})
     *
     */

    public function listcomment(Request $request, PostForum $post, CommentaireRepository $commentRep): Response
    {
        $comments = $commentRep->findAll($post);

        foreach ($comments as $comment) {
            if ($comment->getPostF() == $post) {
                $array[] = [
                    'id' => $comment->getId(),
                    'content' => $comment->getContent(),
                    'username'  => $comment->getUser()->getNom(),
                    'userpren' => $comment->getUser()->getPrenom(),
                    'userphoto' => $comment->getUser()->getPhoto()
                ];
            }
        }
        $test2 = json_encode($array);


        return new Response($test2);
    }

    /**
     * @Route("/show/{id}/like", name="likepost",methods={"GET"})
     */
    public function like(PostForum $post, PostlikeRepository $postlikerepo): Response
    {

        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        if ($post->islikebyuser($user)) {
            $like = $postlikerepo->findOneBy([
                'post' => $post,
                'user' => $user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json(['code' => 200, 'message' => 'like bien supprimer', 'likes' => $postlikerepo->count([
                'post' => $post
            ])], 200);
        }
        $like = new Postlike();
        $like->setUser($user)
            ->setPost($post);
        $entityManager->persist($like);
        $entityManager->flush();
        return $this->json(['code' => 200, 'message' => 'like ajouter', 'likes' => $postlikerepo->count([
            'post' => $post
        ])], 200);
    }
}