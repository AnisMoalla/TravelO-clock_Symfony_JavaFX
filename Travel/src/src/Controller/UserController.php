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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length ;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Email ;
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session ;
use Symfony\Component\Validator\Constraints\Image;
class UserController extends AbstractController
{

  /**
   * @Route("/admins", name="showadmins", methods={"GET"})
   */
  public function admins(): Response
  {
    $session = $this->get('session') ;
    if ($session->has('adminId'))
    {
      $userRepository = $this->getDoctrine()->getRepository(user::class);
      $users = $userRepository->findByRole('admin');
      return $this->render('user/admins.html.twig', [
         'users' => $users
      ]);
    }
    return  $this->redirectToRoute('loginadmin');
  }

  /**
   * @Route("/tourists", name="showtourists", methods={"GET"})
   */
  public function tourists(): Response
  {
    $session = $this->get('session') ;
    if ($session->has('adminId'))
    {
      $userRepository = $this->getDoctrine()->getRepository(user::class);
      $users = $userRepository->findByRole('tourist');
      return $this->render('user/tourists.html.twig', [
         'users' => $users
      ]);
    }
    return  $this->redirectToRoute('loginadmin');
}

/**
 * @Route("/fs", name="showfs", methods={"GET"})
 */
public function fs(): Response
{
  $session = $this->get('session') ;
  if ($session->has('adminId'))
  {
    $userRepository = $this->getDoctrine()->getRepository(user::class);
    $users = $userRepository->findByRole('Fournisseur de service');
    return $this->render('user/fs.html.twig', [
       'users' => $users
    ]);
  }
  return  $this->redirectToRoute('loginadmin');
}


      /**
 * @Route("/loginadmin", name="loginadmin", methods={"GET" , "POST"})
 */
public function loginadmin(Request $request): Response
{
  $session = $this->get('session') ;
  if  (!$session->has('adminId'))
  {
    $defaultData = ['message' => 'Type your message here'];
    $form = $this->createFormBuilder($defaultData)
    ->add('email', EmailType::class ,array(
      'constraints' => array(
       new NotBlank(),
       new Email())))
    ->add('password', PasswordType::class ,array(
      'constraints' => array(
       new NotBlank(),
       new Length(array('min' => 8)))))
    ->add("signIn",SubmitType::class)->getForm() ;
    $form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    // data is an array with "name", "email", and "message" keys
    $data = $form->getData();
    $email = $data['email'] ;
    $password = $data['password'] ;
    $admin=$this->getDoctrine()
    ->getRepository(User::class)
    ->findOneBy(array('email' => $email,'password' =>
    $password , 'role' => 'admin'));
    if ($admin != null)
    {

      // set and get session attributes
      $session = $this->get('session');

      // set and get session attributes
      $session->set('adminId', $admin->getId());
      return $this->redirectToRoute('showadmins');
    } else {
      return $this->render('user/loginadmin.html.twig' , [
        'form' => $form->createView()
      ]);
    }

    }
  } else {
    return $this->redirectToRoute('showadmins') ;
  }
  return $this->render('user/loginadmin.html.twig' , [
    'form' => $form->createView()
  ]);
}

      /**
       * @Route("/profileadmin", name="profile", methods={"GET","POST"})
       */

       public function profile(Request $request): Response
       {
         $session = $this->get('session');
         if ($session->has('adminId'))
         {
           $adminId = $session->get('adminId') ;
           $userRepository = $this->getDoctrine()->getRepository(user::class);
           $admin = $userRepository->find($adminId);
           $form = $this->createFormBuilder($admin)
           ->add('nom')
           ->add('prenom')
           ->add('email')
           ->add('password')
           ->add('age')
           ->add('numero')
           ->add('photo', FileType::class, [
             'label' => 'Profile picture',

             'mapped' => false,

             'required' => false,

             'constraints' => [
               new Image(),
               new NotBlank()
             ]])

           ->add("save",SubmitType::class)->getForm() ;
           $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo)
            {
              $newFilename = uniqid().'.'.$photo->guessExtension();
              try {
              $photo->move(
              $this->getParameter('images_directory'),
              $newFilename );
             } catch (FileException $e) {
             // ... handle exception if something happens during file upload
             }
             $admin->setPhoto($newFilename);
            }

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($admin);
          $entityManager->flush();
          return  $this->redirectToRoute('profile');
         }
         return $this->render('user/profile.html.twig' , ['form' => $form->createView() , 'photo' => $admin->getPhoto()]);
       }

        return  $this->redirectToRoute('loginadmin');
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
          ->add('password',PasswordType::class)
          ->add('age')
          ->add("add",SubmitType::class)->getForm() ;
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            $email = $user->getEmail() ;
            $userRepository = $this->getDoctrine()->getRepository(user::class);
            $oldUser= $userRepository->findByEmail($email);
            if ($oldUser != null)
            {
              return $this->render('user/registertourist.html.twig', [
                  'form' => $form->createView(),
                  'email' => 'email exist'
              ]);
            }

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
           ->add('password' , PasswordType::class)
           ->add('age')
           ->add('numero' , null ,array(
             'constraints' => array(
              new NotBlank(),
              new Length(array('min' => 8)))))
              ->add('cin' , null , array(
             'constraints' => array(
              new NotBlank(),
              new Length(array('min' => 8))
             )))
             ->add('photo', FileType::class, [
             'label' => 'upload photo',

             // unmapped means that this field is not associated to any entity property
             'mapped' => false,

             // make it optional so you don't have to re-upload the PDF file
             // every time you edit the Product details
             'required' => false,

             'constraints' => [
               new Image(),
               new NotBlank()
             ]])
             // unmapped fields can't define their validation using annotations
             // in the associated entity, so you can use the PHP constraint classes,

           ->add("add",SubmitType::class)->getForm() ;
           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {

             $email = $user->getEmail() ;
             $userRepository = $this->getDoctrine()->getRepository(user::class);
             $oldUser= $userRepository->findByEmail($email);
             if ($oldUser != null)
             {
               return $this->render('user/registerfs.html.twig', [
                   'form' => $form->createView(),
                   'email' => 'email exist'
               ]);
             }

             $photo = $form->get('photo')->getData();
             $newFilename = uniqid().'.'.$photo->guessExtension();
             try {
               $photo->move(
              $this->getParameter('images_directory'),
              $newFilename
            );
          } catch (FileException $e) { }
             $user->setPhoto($newFilename);
             $user->setEtat('non valid');
             $user->setRole('Fournisseur de service');
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
             return $this->redirectToRoute('user_new');
           }
           return $this->render('user/registerfs.html.twig', [
               'form' => $form->createView(),
           ]);
         }


         /**
          * @Route("/signup", name="user_new", methods={"GET","POST"})
          */
         public function new(): Response
         {
             return $this->render('user/register.html.twig');
         }

         /**
          * @Route("/{id}/edittourist", name="tourist_edit", methods={"GET","POST"})
          */
         public function edittourist(Request $request, $id): Response
         {

             $userRepository = $this->getDoctrine()->getRepository(user::class);
             $user = $userRepository->find($id);
             $form = $this->createFormBuilder($user)
             ->add('nom')
             ->add('prenom')
             ->add('email')
             ->add('password')
             ->add('age')
             ->add("modifier",SubmitType::class)->getForm() ;
             $form->handleRequest($request);

             if ($form->isSubmitted() && $form->isValid()) {
                 $this->getDoctrine()->getManager()->flush();

                 return $this->redirectToRoute('showtourists');
             }

             return $this->render('user/editinfo.html.twig', [
                 'form' => $form->createView(),
             ]);
         }

         /**
    * @Route("/{id}/deletetourist", name="tourist_delete")
    */
   public function deletetourist(Request $request, $id): Response
   {
           $entityManager = $this->getDoctrine()->getManager();
           $userRepository = $this->getDoctrine()->getRepository(user::class);
           $user = $userRepository->find($id);
           $entityManager->remove($user);
           $entityManager->flush();

       return $this->redirectToRoute('showtourists');
   }

   /**
   * @Route("/{id}/editfs", name="fs_edit", methods={"GET","POST"})
   */
  public function editfs(Request $request, $id): Response
  {

      $userRepository = $this->getDoctrine()->getRepository(user::class);
      $user = $userRepository->find($id);
      $form = $this->createFormBuilder($user)
      ->add('nom')
      ->add('prenom')
      ->add('email')
      ->add('password')
      ->add('age')
      ->add("modifier",SubmitType::class)->getForm() ;
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $this->getDoctrine()->getManager()->flush();

          return $this->redirectToRoute('showfs');
      }

      return $this->render('user/editinfo.html.twig', [
          'form' => $form->createView(),
      ]);
  }

        /**
        * @Route("/{id}/showfs", name="showsinglefs")
        */
        public function showsinglefs(Request $request, $id): Response
        {
                $entityManager = $this->getDoctrine()->getManager();
                $userRepository = $this->getDoctrine()->getRepository(user::class);
                $user = $userRepository->find($id);

                return $this->render('user/showfs.html.twig', [
                    'user' => $user
                ]);
        }

        /**
        * @Route("/addfs", name="addfs")
        */
       public function addFs(Request $request): Response
       {
         $user = new User() ;
         $form = $this->createFormBuilder($user)
         ->add('nom')
         ->add('prenom')
         ->add('email')
         ->add('password' , PasswordType::class)
         ->add('age')
         ->add('numero' , null ,array(
           'constraints' => array(
            new NotBlank(),
            new Length(array('min' => 8)))))
            ->add('cin' , null , array(
           'constraints' => array(
            new NotBlank(),
            new Length(array('min' => 8))
           )))
           ->add('photo', FileType::class, [
           'label' => 'upload photo',

           // unmapped means that this field is not associated to any entity property
           'mapped' => false,

           // make it optional so you don't have to re-upload the PDF file
           // every time you edit the Product details
           'required' => false,

           'constraints' => [
             new Image(),
             new NotBlank()
           ]])
           // unmapped fields can't define their validation using annotations
           // in the associated entity, so you can use the PHP constraint classes,

         ->add("add",SubmitType::class)->getForm() ;
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {

           $email = $user->getEmail() ;
           $userRepository = $this->getDoctrine()->getRepository(user::class);
           $oldUser= $userRepository->findByEmail($email);
           if ($oldUser != null)
           {
             return $this->render('user/addfs.html.twig', [
                 'form' => $form->createView(),
                 'email' => 'email exist'
             ]);
           }

           $photo = $form->get('photo')->getData();
           $newFilename = uniqid().'.'.$photo->guessExtension();
           try {
             $photo->move(
            $this->getParameter('images_directory'),
            $newFilename
          );
        } catch (FileException $e) { }
           $user->setPhoto($newFilename);
           $user->setEtat('valid');
           $user->setRole('Fournisseur de service');
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($user);
           $entityManager->flush();
           return $this->redirectToRoute('showfs');
         }
         return $this->render('user/addfs.html.twig', [
             'form' => $form->createView(),
         ]);
       }

              /**
               * @Route("/addtourist", name="addtourist")
               */
                public function addtourist(Request $request): Response
                {
                  $user = new User() ;
                  $form = $this->createFormBuilder($user)
                  ->add('nom')
                  ->add('prenom')
                  ->add('email')
                  ->add('password',PasswordType::class)
                  ->add('age')
                  ->add("add",SubmitType::class)->getForm() ;
                  $form->handleRequest($request);
                  if ($form->isSubmitted() && $form->isValid()) {
                    $email = $user->getEmail() ;
                    $userRepository = $this->getDoctrine()->getRepository(user::class);
                    $oldUser= $userRepository->findByEmail($email);
                    if ($oldUser != null)
                    {
                      return $this->render('user/addtourist.html.twig', [
                          'form' => $form->createView(),
                          'email' => 'email exist'
                      ]);
                    }
                    $user->setEtat('valid');
                    $user->setRole('tourist');
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('showtourists');
                  }
                  return $this->render('user/addtourist.html.twig', [
                      'form' => $form->createView(),
                  ]);
                }

          /**
          * @Route("/addadmin", name="addadmin")
          */
           public function addadmin(Request $request): Response
           {
             $user = new User() ;
             $form = $this->createFormBuilder($user)
             ->add('nom')
             ->add('prenom')
             ->add('email')
             ->add('password',PasswordType::class)
             ->add('age')
             ->add("add",SubmitType::class)->getForm() ;
             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
               $email = $user->getEmail() ;
               $userRepository = $this->getDoctrine()->getRepository(user::class);
               $oldUser= $userRepository->findByEmail($email);
               if ($oldUser != null)
               {
                 return $this->render('user/addadmin.html.twig', [
                     'form' => $form->createView(),
                     'email' => 'email exist'
                 ]);
               }
               $user->setEtat('valid');
               $user->setRole('admin');
               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($user);
               $entityManager->flush();
               return $this->redirectToRoute('showadmins');
             }
             return $this->render('user/addadmin.html.twig', [
                 'form' => $form->createView(),
             ]);
           }

           /**
       * @Route("/{id}/acceptfs", name="acceptfs")
       */
      public function acceptfs($id) : Response
      {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $user->setEtat('valid') ;
        $entityManager->persist($user) ;
        $entityManager->flush() ;
        return $this->redirectToRoute('showfs');
      }

      /**
         * @Route("/{id}/deletefs", name="fs_delete")
         */
        public function deletefs(Request $request, $id): Response
        {
                $entityManager = $this->getDoctrine()->getManager();
                $userRepository = $this->getDoctrine()->getRepository(user::class);
                $user = $userRepository->find($id);
                $entityManager->remove($user);
                $entityManager->flush();

            return $this->redirectToRoute('showfs');
        }

        /**
          * @Route("/{id}/editadmin", name="admin_edit", methods={"GET","POST"})
          */
         public function editadmin(Request $request, $id): Response
         {

             $userRepository = $this->getDoctrine()->getRepository(user::class);
             $user = $userRepository->find($id);
             $form = $this->createFormBuilder($user)
             ->add('nom')
             ->add('prenom')
             ->add('email')
             ->add('password')
             ->add('age')
             ->add("modifier",SubmitType::class)->getForm() ;
             $form->handleRequest($request);

             if ($form->isSubmitted() && $form->isValid()) {
                 $this->getDoctrine()->getManager()->flush();

                 return $this->redirectToRoute('showadmins');
             }

             return $this->render('user/editinfo.html.twig', [
                 'form' => $form->createView(),
             ]);
         }

         /**
          * @Route("/{id}/deleteadmin", name="admin_delete")
          */
         public function deleteadmin(Request $request, $id): Response
         {
                 $entityManager = $this->getDoctrine()->getManager();
                 $userRepository = $this->getDoctrine()->getRepository(user::class);
                 $user = $userRepository->find($id);
                 $entityManager->remove($user);
                 $entityManager->flush();

             return $this->redirectToRoute('showadmins');
         }



}
