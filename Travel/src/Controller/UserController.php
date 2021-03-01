<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length ;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\TelType;
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/gestion.html.twig', [
          'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newtourist", name="newtourist", methods={"GET","POST"})
     */

     public function newtourist(Request $request): Response
     {
       $user = new User() ;
       $form = $this->createFormBuilder($user)
       ->add('nom')
       ->add('prenom')
       ->add('email')
       ->add('password')
       ->add('age')
       ->add("add",SubmitType::class)->getForm() ;
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         $user->setEtat('valid');
         $user->setRole('tourist');
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($user);
         $entityManager->flush();
         return $this->redirectToRoute('user_index');
       }
       return $this->render('user/registertourist.html.twig', [
           'form' => $form->createView(),
       ]);
     }

     /**
      * @Route("/newfs", name="newfs", methods={"GET","POST"})
      */

      public function newfs(Request $request) : Response
      {
        $user = new User() ;
        $form = $this->createFormBuilder($user)
        ->add('nom')
        ->add('prenom')
        ->add('email')
        ->add('password')
        ->add('age')
        ->add('numero' , null ,array(
          'constraints' => array(
           new NotBlank(),
           new Length(array('min' => 8)))))
           ->add('cin' , null , array(
          'constraints' => array(
           new NotBlank(),
           new Length(array('min' => 3))
       )))
        ->add("add",SubmitType::class)->getForm() ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $user->setEtat('non valid');
          $user->setRole('Fournisseur de service');
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($user);
          $entityManager->flush();
          return $this->redirectToRoute('user_index');
        }
        return $this->render('user/registerfs.html.twig', [
            'form' => $form->createView(),
        ]);
      }

    /**
     * @Route("/signup", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($user->getCin() == null && $user->getRole() == "Fournisseur de service")
            {
              return $this->render('user/register.html.twig', [
                  'user' => $user,
                  'msg' => "pls",
                  'form' => $form->createView(),
              ]);
            }
            if ($user->getRole() == "Fournisseur de service")
            $user->setEtat("non valid") ;
            else
            $user->setEtat("valid") ;
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
