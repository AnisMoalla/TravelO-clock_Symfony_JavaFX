<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Notification;
use App\Entity\Reclamation;
use App\Entity\ReclamationFacc;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository,\Symfony\Component\HttpFoundation\Request $request , PaginatorInterface $paginator): Response
    {
        $reclamation =$reclamationRepository->findAll();
        $paginat = $paginator->paginate(
            $reclamation ,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $paginat,
        ]);
    }


    /**
     * @Route("/{id}/newreclamation", name="newreclamation" )
     */
    public function new(Request $request , $id ,\Swift_Mailer $mailer , PublisherInterface $publisher , SessionInterface $session): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation)
            ->add('type',ChoiceType::class ,
                [
                    'choices' => [
                        'Insatisfaction liée au lieu' => 'Insatisfaction liée au lieu',
                        'Mauvaise organisation' => 'Mauvaise organisation',
                        'horaires de début non respecté' => 'horaires de début non respecté',
                        'other' => 'other'
                    ],
                    'expanded' => true
                ])
            ->add("send",SubmitType::class);

        $form->handleRequest($request);
        $evenemenetRep = $this->getDoctrine()->getRepository(Evenement::class);
        $evenemenet = $evenemenetRep->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRep = $this->getDoctrine()->getRepository(User::class);
            $user = $userRep->find($session->get('user'));
            $reclamation->setEvenement($evenemenet);
            $reclamation->setUser($user);
            $reclamation->setDateReclamtion(new \DateTime());
            if ($reclamation->getType() == 'other') {
                if (empty($reclamation->getDescription())) {
                    return $this->render('reclamation/new.html.twig', [
                        'reclamation' => $reclamation,
                        'nott' => 'not empty',
                        'form' => $form->createView(),
                    ]);
                }

            }

            $email=$user->getEmail();
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('projetpidev992@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'reclamation/sendmail.html.twig',
                        ['reclamation' => $reclamation ]
                    ),
                    'text/html'
                );
            $mailer->send($message);


            $reclamation->setEtat("attente");

            $entityManager = $this->getDoctrine()->getManager();
            $notif = new Notification() ;
            $notif->setReclamation($reclamation) ;

            $entityManager->persist($reclamation);
            $entityManager->persist($notif);
            $entityManager->flush();


            // Notification part
            $update = new Update(
                'http://monsite.com/new',
                json_encode(['nom' => $reclamation->getUser()->getNom() , 'id' => $reclamation->getId() , 'evenement'=> $reclamation->getEvenement()->getNom() , 'date' => $reclamation->getDateReclamtion() , 'type' => 'evenement'])
            );

            // The Publisher service is an invokable object
            $publisher($update);

            return $this->redirectToRoute('lister');

        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/notificationsReclamation", name="notificationsReclamation")
     */
    public function notificationsReclamation()
    {
        $notif = $this->getDoctrine()->getRepository(Notification::class);
        $data = $notif->findAll();
        $res = [];

        foreach ($data as $r)
        {
            $evenement = $r->getReclamation() ;
            if ($evenement != null)
            {
                $evenement = $r->getReclamation()->getEvenement()->getNom();
                $nom = $r->getReclamation()->getUser()->getNom();
                $id = $r->getReclamation()->getId();
                $date = $r->getReclamation()->getDateReclamtion();
                $notif = ['nom' => $nom , 'id' => $id , 'evenement' => $evenement , 'date' => $date , 'type' => 'evenement' ] ;
            }else
            {

                 $fa = $r->getReclamationFamille()->getFacceuil()->getNom();
                 $nom = $r->getReclamationFamille()->getUser()->getNom();
                 $id = $r->getReclamationFamille()->getId();
                 $date = $r->getReclamationFamille()->getDateReclamation();
                 $notif = ['nom' => $nom , 'id' => $id , 'facceuil' => $fa , 'date' => $date , 'type' => 'facceuil' ] ;
            }

            array_push($res,$notif);
        }


        $response = new Response();

        $response->setContent(json_encode([['Notifications' => $res],['nbr' => count($res)] ]));

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');


    return $response;
    }

    /**
     * @Route("/{id}/showreclamation", name="showreclamation", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/editreclamation", name="editreclamation", methods={"GET","POST"})
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation)
            ->add("modifier",SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletereclamation", name="deletereclamation")
     */
    public function delete(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamationRepository = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $reclamationRepository->find($id);
        $entityManager->remove($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('reclamation');
    }

    public function getStatDate($data)
    {
        $res = array(0,0,0,0,0,0,0,0,0,0,0,0) ;
        foreach ($data as $r)
        {

            $index = $r->getDateReclamtion()->format('m') ;
            if ((int)$index >= 10)
                $index = $r->getDateReclamtion()->format('m') - 1 ;
            else
                $index = $r->getDateReclamtion()->format('m')[1] - 1 ;
            $res[$index]++ ;
        }

        return $res ;
    }

    /**
     * @Route("/stat", name="stat", methods={"GET"})
     */
    public function stat(ReclamationRepository $reclamationRepository)
    {
        $data=$reclamationRepository->findAll();
        $res = $this->getStatDate($data) ;
        $etatRef = 0 ;
        $etatAcc = 0 ;
        $etatAtt = 0 ;
        $type1 = 0 ;
        $type2= 0 ;
        $type3= 0 ;
        $type4= 0 ;
        foreach ($data as $t)
        {
            $type=$t->getType();
            if ($type=="Insatisfaction liée au lieu"){
                $type1++;
            }
            elseif ($type=="Mauvaise organisation"){
                $type2++;
            }
            elseif ($type=="horaires de début non respecté"){
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
        $reclamation= $reclamationRepository->countbydate();
        $dates = [];
        $reclamationcount = [];

        foreach ($reclamation as $reclame){
            $dates[]= $reclame['date_reclamtion'];
            $reclamationcount[]=$reclame['count'];

    }
        $choice=['Insatisfaction liée au lieu','Mauvaise organisation',
            'horaires de début non respecté','other'];

        return $this->render('/reclamation/stat.html.twig' ,
            ['type1' => $type1,'type2' => $type2,'type3' => $type3,'type4' => $type4,
            'choice'=>json_encode($choice),
                'dates'=>json_encode($dates),
                'reclamationcount'=>json_encode($reclamationcount) ,
                'etat' => [$etatRef,$etatAcc,$etatAtt] ,
                'res' => $res
            ]
);
    }



    /**
     * @Route("/lister", name="lister", methods={"GET"})
     */
    public function lister(ReclamationRepository $reclamationRepository , SessionInterface $session)
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }
        $userRep = $this->getDoctrine()->getRepository(User::class);
        $user = $userRep->find($session->get('user'));
        $reclamations = $reclamationRepository->findByUser($user);
        return $this->render('/reclamation/mesreclamation.html.twig',
        ['liste' => $reclamations]);
    }

    /**
     * @Route("/{id}/vu", name="vu", methods={"GET","POST"})
     */
    public function vu(Request $request, $id)
    {
        $reclamationRepository = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamations = $reclamationRepository->find($id);

        $notifRep = $this->getDoctrine()->getRepository(Notification::class);
        $notif = $notifRep->findOneBy(['reclamation' =>$reclamations]);

        if ($notif !=null)
        {
            $manager = $this->getDoctrine()->getManager() ;
            $manager->remove($notif);
            $manager->flush();
        }


        return $this->render('/reclamation/vu.html.twig',
        ['reclamation' => $reclamations]);
    }

    /**
     * @Route("/{id}/{etat}/gerer", name="gerer", methods={"GET","POST"})
     */
    public function gerer($id , $etat  ,\Swift_Mailer $mailer)
    {
        $reclamationRep = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $reclamationRep->find($id);
        $reclamation->setEtat($etat);
        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();

        $email=$reclamation->getUser()->getEmail();
        $message = (new \Swift_Message('about your reclamation'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'reclamation/mailreclamation.html.twig',
                    ['reclamation' => $reclamation ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('reclamation');
    }
}
