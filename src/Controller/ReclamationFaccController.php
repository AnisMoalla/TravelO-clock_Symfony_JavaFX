<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Facceuil;
use App\Entity\Notification;
use App\Entity\Reclamation;
use App\Entity\ReclamationFacc;
use App\Entity\User;
use App\Form\ReclamationFaccType;
use App\Form\ReclamationType;
use App\Repository\ReclamationFaccRepository;
use App\Repository\ReclamationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;


class ReclamationFaccController extends AbstractController
{
    /**
     * @Route("/reclamationFacc", name="reclamation_facc_index", methods={"GET"})
     */
    public function index(ReclamationFaccRepository $reclamationFaccRepository ,\Symfony\Component\HttpFoundation\Request $request , PaginatorInterface $paginator): Response
    {

        $paginat = $paginator->paginate(
            $reclamationFaccRepository->findAll() ,
            $request->query->getInt('page',1),
            1
        );
        return $this->render('reclamation_facc/index.html.twig', [
            'reclamation_faccs' => $paginat,
        ]);
    }

    /**
     * @Route("/{id}/newreclamationFacc", name="reclamation_facc_new", methods={"GET","POST"})
     */
    public function new(Request $request, $id ,\Swift_Mailer $mailer ,  PublisherInterface $publisher , SessionInterface $session) : Response
    {
        $reclamationFacc = new ReclamationFacc();
        $form = $this->createForm(ReclamationFaccType::class, $reclamationFacc)
        ->add('type',ChoiceType::class ,
        [
            'choices' => [
                'aaaa' => 'aaaa',
                'violence organisee' => 'violence organisee',
                'violence-organisee' => 'violence-organisee',
                'other' => 'other'
            ],
            'expanded' => true
        ])
        ->add("send",SubmitType::class);

        $facceuilRep = $this->getDoctrine()->getRepository(Facceuil::class);
        $facceuil = $facceuilRep->find($id);
        if($facceuil == null)
        {
            return $this->redirectToRoute("front") ;
        }
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $userRep = $this->getDoctrine()->getRepository(User::class);
            $user = $userRep->find($session->get('user'));
            $reclamationFacc->setFacceuil($facceuil);
            $reclamationFacc->setUser($user);
            $reclamationFacc->setEtat("attente");
            $reclamationFacc->setDateReclamation(new \DateTime());
            if ($reclamationFacc->getType() == 'other') {
                if (empty($reclamationFacc->getDescription())) {
                    return $this->render('reclamation/new.html.twig', [
                        'reclamationFacc' => $reclamationFacc,
                        'nott' => 'not empty',
                        'form' => $form->createView(),
                    ]);
                }

            }
            $msg = "bonjour Mr/Mms:".$reclamationFacc->getUser().", \n  votre reclamation sur la Facceuil "
                .$reclamationFacc->getFacceuil()."  de type : "
                .$reclamationFacc->getType()." avec la discription :"
                .$reclamationFacc->getDescription()." \n a ete envoyer .";

            $email=$user->getEmail();
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('projetpidev992@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'reclamation_facc/sendmail.html.twig',
                        ['reclamationFacc' => $reclamationFacc ]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $entityManager = $this->getDoctrine()->getManager();

            $notif = new Notification() ;
            $notif->setReclamationFamille($reclamationFacc) ;

            $entityManager->persist($reclamationFacc);
            $entityManager->persist($notif);

            $entityManager->flush();

            // Notification part
            $update = new Update(
                'http://monsite.com/new',
                json_encode(['nom' => $reclamationFacc->getUser()->getNom() , 'id' => $reclamationFacc->getId() , 'facceuil'=> $reclamationFacc->getFacceuil()->getNom() , 'date' => $reclamationFacc->getDateReclamation() , 'type' => 'facceuil' ])
            );

            // The Publisher service is an invokable object
            $publisher($update);



            return $this->redirectToRoute('listerfa');
        }

        return $this->render('reclamation_facc/new.html.twig', [
            'reclamation_facc' => $reclamationFacc,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/vufa", name="vufa", methods={"GET","POST"})
     */
    public function vu(Request $request, $id)
    {
        $reclamationRepository = $this->getDoctrine()->getRepository(ReclamationFacc::class);
        $reclamations = $reclamationRepository->find($id);

        $notifRep = $this->getDoctrine()->getRepository(Notification::class);
        $notif = $notifRep->findOneBy(['reclamationFamille' =>$reclamations]);

        if ($notif !=null)
        {
            $manager = $this->getDoctrine()->getManager() ;
            $manager->remove($notif);
            $manager->flush();
        }


        return $this->render('/reclamation_facc/vufa.html.twig',
            ['reclamation' => $reclamations]);
    }


    /**
     * @Route("/{id}/{etat}/gererfa", name="gererfa", methods={"GET","POST"})
     */
    public function gerer($id , $etat  ,\Swift_Mailer $mailer)
    {
        $reclamationRep = $this->getDoctrine()->getRepository(ReclamationFacc::class);
        $reclamation = $reclamationRep->find($id);
        $reclamation->setEtat($etat);
        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();

        $email=$reclamation->getUser()->getEmail();
        $message = (new \Swift_Message('about your reclamation famille d acceuil'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'reclamation_facc/mailreclamation.html.twig',
                    ['reclamation' => $reclamation ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('reclamation');
    }

    /**
     * @Route("/{id}/showreclamationFacc", name="reclamation_facc_show", methods={"GET"})
     */
    public function show(ReclamationFacc $reclamationFacc): Response
    {
        return $this->render('reclamation_facc/show.html.twig', [
            'reclamation_facc' => $reclamationFacc,
        ]);
    }

    /**
     * @Route("/{id}/editreclamationFacc", name="reclamation_facc_edit", methods={"GET","POST"})
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
     * @Route("/{id}/deletereclamationFacc", name="reclamation_facc_delete")
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


    /**
     * @Route("/statfaccreclamationFacc", name="statfaccreclamationFacc", methods={"GET"})
     */
    public function statfacc(ReclamationFaccRepository $faccRepository)
    {
        $data=$faccRepository->findAll();
        $type1 = 0 ;
        $type2= 0 ;
        $type3= 0 ;
        $type4= 0 ;
        $etatRef = 0 ;
        $etatAcc = 0 ;
        $etatAtt = 0 ;
        foreach ($data as $t)
        {
            $type=$t->getType();
            if ($type=="aaaa"){
                $type1++;
            }
            elseif ($type=="violence organisee"){
                $type2++;
            }
            elseif ($type=="violence-organisee"){
                $type3++;
            }
            elseif ($type=="other"){
                $type4++;
            }

            if ($t->getEtat() == 'attente')
            {
                $etatAtt++ ;
            } elseif ($t->getEtat() == 'accepter')
            {
                $etatAcc++ ;
            } elseif ($t->getEtat() == 'refuser')
            {
                $etatRef++ ;
            }
        }
        $reclamation= $faccRepository->countbydate();
        $dates = [];
        $reclamationcount = [];

        foreach ($reclamation as $reclame){
            $dates[]= $reclame['date_reclamation'];
            $reclamationcount[]=$reclame['count'];

        }
        $choice=['aaaa','violence organisee','violence-organisee','other'];
        return $this->render('/reclamation_facc/statfacc.html.twig' ,
            ['type1' => $type1,'type2' => $type2,'type3' => $type3,'type4' => $type4,
                'choice'=>json_encode($choice),
                'dates'=>json_encode($dates),
                'reclamationcount'=>json_encode($reclamationcount),
                'etat' => [$etatRef,$etatAcc,$etatAtt] ]
        );
    }

    /**
     * @Route("/listerfa", name="listerfa", methods={"GET"})
     */
    public function lister(ReclamationFaccRepository $reclamationRepository , SessionInterface $session)
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }
        $userRep = $this->getDoctrine()->getRepository(User::class);
        $user = $userRep->find($session->get('user'));
        $reclamations = $reclamationRepository->findByUser($user);
        return $this->render('/reclamation_facc/mesreclamation.html.twig',
            ['liste' => $reclamations]);
    }

}
