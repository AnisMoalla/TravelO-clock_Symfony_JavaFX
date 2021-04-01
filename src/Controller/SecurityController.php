<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecurityController extends AbstractController
{
    /**
     * @Route("/newtourist", name="newtourist")
     */
    public function newtourist(Request $request, UserPasswordEncoderInterface $userPasswordEncoder , \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user)->add("signup",SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setVerified("false") ;
            $user->setRoles(["ROLE_TOURIST"]);
            $user->setEtat("non valid");
            $user->setCreatedAt(new \DateTime());
            $user->setActivationtoken(md5(uniqid())) ;
            $mn = $this->getDoctrine()->getManager();
            $mn->persist($user);
            $mn->flush();


            // send mail
            $message = (new \Swift_Message('Welcome to our website'))
                ->setFrom('projetpidev992@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'security/emailVerifAccount.html.twig',
                        ['token' => $user->getActivationtoken() , 'name' => $user->getPrenom()]
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);

            return $this->render('security/newAccount.html.twig');

        }
        return $this->render('security/inscription.html.twig', array(
            'form'=> $form->createView(),
        ));

    }


    /**
     * @Route("/newfs", name="newfs")
     */
    public function newfs(Request $request, UserPasswordEncoderInterface $userPasswordEncoder , \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user)
            ->add('numero' , IntegerType::class ,array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 8)))))
            ->add('cin' , IntegerType::class , array(
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
            ->add("signup",SubmitType::class);

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

            $user->setPhoto($newFilename);
            $hash = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setEtat("non valid");
            $user->setVerified("false") ;
            $user->setRoles(["ROLE_PROVIDER"]);
            $user->setCreatedAt(new \DateTime());
            $user->setActivationtoken(md5(uniqid())) ;
            $mn = $this->getDoctrine()->getManager();
            $mn->persist($user);
            $mn->flush();


            // send mail
            $message = (new \Swift_Message('Welcome to our website'))
                ->setFrom('projetpidev992@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'security/emailVerifAccount.html.twig',
                        ['token' => $user->getActivationtoken() , 'name' => $user->getPrenom()]
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);

            return $this->render('security/newAccount.html.twig');

        }
        return $this->render('security/newAccountfs.html.twig', array(
            'form'=> $form->createView(),
        ));

    }

    /**
     * @Route("/prof", name="prof")
     */
    public function prof(SessionInterface $session , Request $request): Response
    {

        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        $arr = array() ;
        $form = $this->createFormBuilder($arr)
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
            ->add("save",SubmitType::class)->getForm() ;

        $form->handleRequest($request) ;
        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            $newFilename = uniqid() . '.' . $photo->guessExtension();
            try {
                $photo->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $userRep = $this->getDoctrine()->getRepository(User::class);
            $user = $userRep->find($session->get("user")->getId());
            $em = $this->getDoctrine()->getManager() ;
            $user->setPhoto($newFilename);
            $em->persist($user);
            $em->flush();
            $session->set("user",$user);
            return $this->redirectToRoute("prof") ;
        }

        return $this->render('security/profile.html.twig' , ['form' => $form->createView()]);
    }


    /**
     * @Route("/updateprof", name="updateprof")
     */
    public function updateinfo(SessionInterface $session , Request $request , UserPasswordEncoderInterface $userPasswordEncoder ): Response
    {

        if (!$session->has("user")) {
            return $this->redirectToRoute("login");
        }

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($session->get("user")->getId());

        $form = $this->createForm(UserType::class, $user)
            ->add('numero', IntegerType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 8)))))
            ->add('cin', IntegerType::class, array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 8))
                )))
            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes,
            ->add("update", SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $session->set("user",$user);
            return $this->redirectToRoute('prof');
        }

        return $this->render('security/updateProfile.html.twig',['form' => $form->createView()]);
    }
    /**
     * @Route("/verifAccount/{token}", name="verifAccount")
     */
    public function verifAccount($token): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneByActivationtoken($token);
        if ($user == null)
        {
            return $this->render('security/notfound.html.twig');
        }

        $user->setActivationtoken(null) ;
        $user->setVerified("true");
        $userManager = $this->getDoctrine()->getManager();
        $userManager->persist($user);
        $userManager->flush();
        return $this->render('security/AccountVerified.html.twig');
    }

    /**
     * @Route("/forgetpassword", name="forgetpassword")
     */
    public function forgetpassword(Request $request , \Swift_Mailer $mailer): Response
    {
        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class ,array(
                'constraints' => array(
                    new NotBlank(),
                    new Email())))
            ->add("send",SubmitType::class)
            ->getForm() ;

        $form->handleRequest($request) ;
        if ($form->isSubmitted() && $form->isValid())
        {
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $data = $form->getData();
            $user = $userRepository->findOneByEmail($data['email']);
            if ($user == null)
            {
                return $this->render('security/forgetpassword.html.twig' ,
                    ['form' => $form->createView() , 'check' => 'true']
                );
            }
            $user->setResettoken(md5(uniqid()));
            $manager = $this->getDoctrine()->getManager() ;
            $manager->persist($user);
            $manager->flush();

            // send mail
            $message = (new \Swift_Message('Reset your password'))
                ->setFrom('projetpidev992@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'security/resetpassword.html.twig',
                        ['token' => $user->getResettoken()]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            return $this->render('security/passwordchange.html.twig');
        }
        return $this->render('security/forgetpassword.html.twig' , ['form' => $form->createView() ]);
    }

    /**
     * @Route("/checkforgetpass/{token}", name="checkforgetpass")
     */
    public function checkforgetpass($token , Request $request , UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneByResettoken($token);
        if ($user == null)
        {
            return $this->render('security/notfound.html.twig');
        }

        $data = [] ;
        $form = $this->createFormBuilder($data)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('change',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $form->getData()['password'];
            $hash = $userPasswordEncoder->encodePassword($user, $password);
            $user->setPassword($hash) ;
            $user->setResettoken(null);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login') ;

        }
        return $this->render('security/changepassform.html.twig',['form' => $form->createView()]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils , SessionInterface $session): Response
    {
        if ($session->has("user"))
        {
            return $this->redirectToRoute("fronthome");
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error)
        {
            return $this->render('security/login.html.twig', array('check' => true ));
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(AuthenticationUtils $authenticationUtils , SessionInterface $session):Response
    {
       if ($this->getUser()->getVerified() == "false")
       {
           return $this->render('security/login.html.twig', array(
               'nonValid'         => true,
           ));
       }
       $session->set("user",$this->getUser());
       return $this->redirectToRoute("fronthome") ;
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }


    /**
     * @Route("/logoutt", name="logoutt")
     */
    public function logoutt(SessionInterface $session) : Response
    {
        $session->remove("user");
        return $this->redirectToRoute("logout");
    }




}