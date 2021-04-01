<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use http\Env\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Validator\Constraints\Length ;
use Symfony\Component\Validator\Constraints\Image ;


class UserController extends AbstractController
{


    //functions stat
    public function getStatAges($users)
    {
        $ages = array(0,0,0,0,0);

        foreach ($users as $user)
        {
            if ($user->getAge() >= 18 && $user->getAge() < 25)
            {
                $ages[0]++;
            }
            elseif ($user->getAge() >= 25 && $user->getAge() < 35 )
            {
                $ages[1]++;
            }
            elseif ($user->getAge() >= 35 && $user->getAge() < 45 )
            {
                $ages[2]++;
            }
            elseif ($user->getAge() >= 45 && $user->getAge() < 55 )
            {
                $ages[3]++;
            }
            elseif ($user->getAge() >= 55)
            {
                $ages[4]++ ;
            }
        }
        return $ages ;
    }


    public function getStatVerif($users)
    {
        $verif = [0,0] ;
        foreach ($users as $user)
        {
            if ($user->getVerified() == "true")
            {
             $verif[0]++ ;
            } else
            {
                $verif[1]++ ;
            }
        }
        return $verif ;
    }

    public function getStatDate($users)
    {
        $res = array(0,0,0,0,0,0,0,0,0,0,0,0) ;
        foreach ($users as $user)
        {
            $index = $user->getCreatedAt()->format('m')[1] - 1 ;
            $res[$index]++ ;
        }
        return $res ;
    }

    /**
     * @Route("/statsadmin", name="statsadmin", methods={"GET"})
     */
    public function statsadmin(): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users= $userRepository->findAll();

        $nbreTourists = 0 ;
        $nbreAdmins = 0 ;
        $nbreSP = 0 ;

        foreach ($users as $user)
        {
            if ($user->getRoles()[0]=="ROLE_TOURIST")
            {
                $nbreTourists++;
            }
            elseif ($user->getRoles()[0]=="ROLE_PROVIDER")
            {
                $nbreSP++;
            }
            elseif ($user->getRoles()[0]=="ROLE_ADMIN")
            {
                $nbreAdmins++;
            }

        }

        $statVerif = $this->getStatVerif($users) ;
        $statages = $this->getStatAges($users) ;
        $statdate = $this->getStatDate($users) ;
        return $this->render('user/stats.html.twig' ,
            [
                "nbreTourists" => $nbreTourists ,
                "nbreAdmins" => $nbreAdmins ,
                "nbreSP" => $nbreSP ,
                "statages" => $statages ,
                "statVerif" => $statVerif ,
                "statDate" => $statdate
            ]

        ) ;

    }


    // Service Provider

     /**
     * @Route("/fs", name="showfs", methods={"GET"})
     */
    public function fs(PaginatorInterface $paginator , \Symfony\Component\HttpFoundation\Request $request , SessionInterface $session): Response
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute("fronthome");
        }
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $users = $userRepository->findByRole("ROLE_PROVIDER");
        $users = $paginator->paginate(
            $users ,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('user/fs.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/addfs", name="addfs")
     */
    public function addFs(\Symfony\Component\HttpFoundation\Request $request , UserPasswordEncoderInterface $userPasswordEncoder): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class,$user)
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

            ->add("add",SubmitType::class)   ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            $newFilename = uniqid().'.'.$photo->guessExtension();
            try {
                $photo->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) { }

            $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setPhoto($newFilename);
            $user->setEtat('valid');
            $user->setCreatedAt(new \DateTime() );
            $user->setVerified("true");
            $user->setRoles(["ROLE_PROVIDER"]);
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
     * @Route("/{id}/editfs", name="fs_edit", methods={"GET","POST"})
     */
    public function editfs(\Symfony\Component\HttpFoundation\Request $request, $id , UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class,$user)->add("modifier",SubmitType::class) ;
        $form->handleRequest($request);
        $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('showfs');
        }

        return $this->render('user/editinfo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletefs", name="fs_delete")
     */
    public function deletefs($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('showfs');
    }

    /**
     * @Route("/{id}/showfs", name="showsinglefs")
     */
    public function showsinglefs( $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);

        return $this->render('user/showfs.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/acceptfs", name="acceptfs")
     */
    public function acceptfs($id) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $user->setEtat('valid') ;
        $entityManager->persist($user) ;
        $entityManager->flush() ;
        return $this->redirectToRoute('showfs');
    }

    // Tourists part

    /**
     * @Route("/tourists", name="showtourists", methods={"GET"})
     */
    public function tourists(SessionInterface $session , PaginatorInterface $paginator , \Symfony\Component\HttpFoundation\Request $request): Response
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute("fronthome");
        }

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findByRole("ROLE_TOURIST");
        $paginat = $paginator->paginate(
            $users ,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('user/tourists.html.twig', [
            'users' => $paginat
        ]);
    }


    /**
     * @Route("/addtourist", name="addtourist")
     */
    public function addtourist(\Symfony\Component\HttpFoundation\Request $request , UserPasswordEncoderInterface $userPasswordEncoder): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $user = new User() ;
        $form = $this->createForm(UserType::class,$user)
            ->add("add",SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setEtat('valid');
            $user->setRoles(['ROLE_TOURIST']);
            $user->setVerified("true");
            $user->setCreatedAt(new \DateTime());
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
     * @Route("/{id}/deletetourist", name="tourist_delete")
     */
    public function deletetourist(\Symfony\Component\HttpFoundation\Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('showtourists');
    }

    /**
     * @Route("/{id}/edittourist", name="tourist_edit", methods={"GET","POST"})
     */
    public function edittourist(\Symfony\Component\HttpFoundation\Request $request, $id , UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class,$user)
            ->add("modifier",SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('showtourists');
        }

        return $this->render('user/editinfo.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    // admin

    /**
     * @Route("/admins", name="showadmins", methods={"GET"})
     */
    public function admins(\Symfony\Component\HttpFoundation\Request $request , PaginatorInterface $paginator , SessionInterface $session): Response
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute("fronthome");
        }

        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $users = $userRepository->findByRole("ROLE_ADMIN");
        $paginat = $paginator->paginate(
            $users ,
            $request->query->getInt('page',1),
            1
        );
        return $this->render('user/admins.html.twig', [
            'users' => $paginat
        ]);

    }

    /**
     * @Route("/addadmin", name="addadmin")
     */
    public function addadmin(\Symfony\Component\HttpFoundation\Request $request , UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User() ;
        $form = $this->createForm(UserType::class,$user)
        ->add('add',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setEtat('valid');
            $user->setVerified("true");
            $user->setRoles(['ROLE_ADMIN']);
            $user->setCreatedAt(new \DateTime());
            $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
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
     * @Route("/{id}/editadmin", name="admin_edit", methods={"GET","POST"})
     */
    public function editadmin(\Symfony\Component\HttpFoundation\Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class,$user)
            ->add("modifier",SubmitType::class) ;
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
    public function deleteadmin(\Symfony\Component\HttpFoundation\Request   $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $user = $userRepository->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('showadmins');
    }

    /**
     * @Route("/profileadmin", name="profile", methods={"GET","POST"})
     */

    public function profile(\Symfony\Component\HttpFoundation\Request $request,UserPasswordEncoderInterface $userPasswordEncoder , SessionInterface $session): Response
    {

        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute("fronthome");
        }

        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $admin = $userRepository->find($session->get("user")->getId());
        $form = $this->createForm(UserType::class,$admin)
                ->add('numero')
                ->add('photo', FileType::class, [
                    'label' => 'Profile picture',

                    'mapped' => false,

                    'required' => false,

                    'constraints' => [
                        new Image(),
                    ]])

                ->add("save",SubmitType::class) ;
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

            $hash = $userPasswordEncoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();
            $session->set('user', $admin);
            return  $this->redirectToRoute('profile');
            }
        return $this->render('user/profile.html.twig' , ['form' => $form->createView() , 'photo' => $admin->getPhoto()]);

    }


    /**
     * @Route("/profileuser", name="profileuser", methods={"GET","POST"})
     */

    public function profileUser(\Symfony\Component\HttpFoundation\Request $request,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        if ($this->getUser() == null)
        {
            return $this->redirectToRoute("login");
        }
        $userRepository = $this->getDoctrine()->getRepository(user::class);
        $admin = $userRepository->find($this->getUser()->getId());
        $form = $this->createForm(UserType::class,$admin)
            ->add('numero')
            ->add('photo', FileType::class, [
                'label' => 'Profile picture',

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new Image(),
                ]])

            ->add("save",SubmitType::class) ;
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

            $hash = $userPasswordEncoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();
            return  $this->redirectToRoute('profileuser');
        }
        return $this->render('user/profileuser.html.twig' , ['form' => $form->createView() , 'photo' => $admin->getPhoto()]);

    }






}
