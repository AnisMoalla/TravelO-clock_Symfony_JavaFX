<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\EvenementCommentaire;
use App\Entity\Notification;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\EvenementType;
use App\Repository\EvenementCommentaireRepository;
use App\Repository\EvenementRepository;
use App\Repository\EventLikeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EventLike;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf;
use Dompdf\Options;



class EvenementController extends AbstractController
{

    //functions stat
    public function getStatPrix($evenements)
    {
        $prix = array(0,0,0,0,0);

        foreach ($evenements as $evenement)
        {
            if ($evenement->getPrix() >= 1 && $evenement->getPrix() < 50)
            {
                $prix[0]++;
            }
            elseif ($evenement->getPrix() >= 50 && $evenement->getPrix() < 100 )
            {
                $prix[1]++;
            }
            elseif ($evenement->getPrix() >= 100 && $evenement->getPrix() < 150 )
            {
                $prix[2]++;
            }
            elseif ($evenement->getPrix() >= 150 && $evenement->getPrix() < 200 )
            {
                $prix[3]++;
            }
            elseif ($evenement->getPrix() >= 200)
            {
                $prix[4]++ ;
            }
        }
        return $prix ;
    }


    public function getStatNbr($evenements)
    {
        $nbr = [0,0,0,0,0] ;
        foreach ($evenements as $evenement)
        {
            if ($evenement->getNbrPlaces() >= 1 && $evenement->getNbrPlaces() < 5)
            {
                $nbr[0]++;
            }
            elseif ($evenement->getNbrPlaces() >= 5 && $evenement->getNbrPlaces() < 10 )
            {
                $nbr[1]++;
            }
            elseif ($evenement->getNbrPlaces() >= 10 && $evenement->getNbrPlaces() < 15 )
            {
                $nbr[2]++;
            }
            elseif ($evenement->getNbrPlaces() >= 15 && $evenement->getNbrPlaces() < 20 )
            {
                $nbr[3]++;
            }
            elseif ($evenement->getNbrPlaces() >= 20)
            {
                $nbr[4]++ ;
            }
        }
        return $nbr ;
    }

    public function getStatDate($evenements)
    {
        $res = array(0,0,0,0,0,0,0,0,0,0,0,0) ;
        foreach ($evenements as $evenement)
        {
            $index = $evenement->getDateDebut()->format('m')[1] - 1 ;
            $res[$index]++ ;
        }
        return $res ;
    }

    /**
     * @Route("/statsevenement", name="statsevenement", methods={"GET"})
     */
    public function statsevenements(): Response
    {
        $evenementRepository = $this->getDoctrine()->getRepository(Evenement::class);
        $evenements= $evenementRepository->findAll();

        $statnbr = $this->getStatPrix($evenements) ;
        $statprix = $this->getStatNbr($evenements) ;
        $statdate = $this->getStatDate($evenements) ;
        return $this->render('evenement/evenementsstatistiques.html.twig' ,
            [
                "statprix" => $statprix ,
                "statnbr" => $statnbr ,
                "statdate" => $statdate,
            ]

        ) ;

    }

    /**
     * @Route("/evenementsstat", name="evenementsstat", methods={"GET"})
     */
    public function statistiqueAction(){
        $pieChart = new PieChart();
        $em= $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT o  ,UPPER(e.nom) as nom ,COUNT(o.id) as num FROM App\Entity\Offre o
        join App\Entity\Evenement e WITH e.id=o.evenement GROUP BY o.evenement');
        $reservations=$query->getScalarResult();
        $data= array();
        $stat=['evenement_id', 'id'];
        $i=0;
        array_push($data,$stat);

        $ln= count($reservations);
        for ($i=0 ;$i<count($reservations);$i++){
            $stat=array();
            array_push($stat,$reservations[$i]['nom'],$reservations[$i]['num']/$ln);
            $stat=[$reservations[$i]['nom'],$reservations[$i]['num']*100/$ln];

            array_push($data,$stat);
        }
        $pieChart->getData()->setArrayToDataTable( $data );
        $pieChart->getOptions()->setTitle('Nombre Des Offres à Chaque Evenements!');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('evenement/evenementsstat.html.twig', array('piechart' => $pieChart));
    }

    /**
     * @Route("/evenementsliste", name="evenementsliste", methods={"GET"})
     */
    public function print(EvenementRepository $evenementRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('evenement/evenementsliste.html.twig', [
            'title' => "Welcome to our PDF Test",
            'evenements' => $evenementRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Evenementspdf.pdf", [
            "Attachment" => true

        ]);
    }

    /**
     * @Route("/mailevent", name="mailevent", methods={"GET","POST"})
     */
    public function mailevent(\Swift_Mailer $mailer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');


        $message = (new \Swift_Message('Mail Admin Evenements'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                "Mail Evenements"
            )
        ;

        $mailer->send($message);
        return $this->redirectToRoute("evenements");
    }

    /**
     * @Route("/rateEvenement", name="rateEvenement", methods={"POST"})
     */
    public function rateAction(Request $request){
        $data = $request->getContent();
        $obj = json_decode($data,true);

        $em = $this->getDoctrine()->getManager();
        $rate =$obj['rate'];
        $id = $obj['evenement'];
        $evenement = $em->getRepository(Evenement::class)->find($id);
        $note = ($evenement->getRate()*$evenement->getVote() + $rate)/($evenement->getVote()+1);
        $evenement->setVote($evenement->getVote()+1);
        $evenement->setRate($note);
        $em->persist($evenement);
        $em->flush();
        return new Response($evenement->getRate());
    }

    /**
     * @Route("/evenement/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/newevenement", name="evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request , SessionInterface $session): Response
    {

        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute("fronthome");
        }

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement)
        ->add('photo', FileType::class, [
          'label' => 'Profile picture',

          'mapped' => false,

          'required' => false,

          'constraints' => [
            new Image(),
            new NotBlank(),
          ]])
        ->add('ajouter',SubmitType::class);
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
           $evenement->setImage($newFilename);
          }
            $userRep = $this->getDoctrine()->getRepository(User::class);
            $user = $userRep->find($session->get('user'));
            $evenement->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenements');
        }
        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/editevenement", name="evenement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenements');
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deleteevenement", name="evenement_delete")
     */
    public function delete(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $evenementRepository = $this->getDoctrine()->getRepository(Evenement::class);
        $evenement = $evenementRepository->find($id);

        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class);

        $reclamation = $reclamation->findByEvenement($evenement);
        foreach ($reclamation as $rec)
        {
            $entityManager->remove($rec);
        }
        $entityManager->remove($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenements');
        $this->get('session')->getFlashBag()->add('notice','Evenement Supprimé');
    }

    /**
     * @Route("/evenements", name="evenements", methods={"GET"})
     */
    public function index(Request $request , PaginatorInterface $paginator): Response
    {
        $donnees=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        $events = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('evenement/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/frontEvenements", name="frontEvenements", methods={"GET"})
     */
    public function frontEvenement(EvenementRepository $evenementRepository , PaginatorInterface $paginator , Request $request): Response
    {
        $donnees=$this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();

        $evenements = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('evenement/frontevents.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    /**
     * @Route("/newevenementfront", name="new_evenement", methods={"GET","POST"})
     */
    public function newf(Request $request): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement)
            ->add('photo', FileType::class, [
                'label' => 'Profile picture',

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new Image(),
                    new NotBlank()
                ]])
            ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && ($evenement->getDateDebut() > new \DateTime () && $evenement->getDateFin() > $evenement->getDateDebut())) {
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
                $evenement->setImage($newFilename);
            }
            $evenement->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('frontEvenements');
        }
        $evenement->setUser($this->getUser());

        return $this->render('evenement/newfront.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editevenementfront", name="edit_evenement", methods={"GET","POST"})
     */
    public function editf(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('frontmesEvenements');
        }

        return $this->render('evenement/editfront.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deleteevenementfront", name="delete_evenement")
     */
    public function deletef(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $evenementRepository = $this->getDoctrine()->getRepository(Evenement::class);
        $evenement = $evenementRepository->find($id);
        $entityManager->remove($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('frontEvenements');
        $this->get('session')->getFlashBag()->add('notice','Evenement Supprimé');
    }

    /**
     * @Route("/frontmesEvenements", name="frontmesEvenements", methods={"GET"})
     */
    public function frontmesEvenement(EvenementRepository $evenementRepository , SessionInterface $session): Response
    {

        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }
        return $this->render('evenement/frontmesevents.html.twig', [
            'evenements' => $evenementRepository->findByUser($session->get('user')),
        ]);
    }

    /**
     * @Route("/{id}/frontEvenementa", name="frontEvenementa", methods={"GET"})
     */
    public function frontEvenementa(EvenementRepository $evenementRepository ,  $id): Response
    {
        return $this->render('evenement/frontevent.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'evenement' => $evenementRepository->find($id),
        ]);
    }

    /**
     * @Route("/{id}/neweventcomment",name="neweventcomment")
     *
     */
    public function addcomment(Request $request, Evenement $evenement, EvenementCommentaireRepository $commenteventRep): Response
    {
        $user=$this->getUser();
        $comment = new EvenementCommentaire();
        $comment->setEvent($evenement);

        $comment->setContent($request->request->get('content'));
        $comment->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($comment);

        $entityManager->flush();



        return $this->json(['code' => 200, 'message' => $comment->getContent()], 200);
    }

    /**
     * @Route("/{id}/listeventcomment",name="listeventcomment",methods={"GET"})
     *
     */
    public function listcomment(Request $request, Evenement $evenement, EvenementCommentaireRepository  $commentRep): Response
    {
        $comments = $commentRep->findAll($evenement);
        $user=$this->getUser();
        $array=[
        ];
        foreach ($comments as $comment) {
            if ($comment->getEvent() == $evenement) {

                $array[] = [
                    'id' => $comment->getId(),
                    'content' => $comment->getContent(),
                    'user' => $comment->getUser(),
                ];
            }
        }
        $test2 = json_encode($array);


        return new Response($test2);


    }

    /**
     * @Route("/{id}/likeevenement", name="likeevenement")
     */
    public function like(Evenement $evenement, EventLikeRepository $evenementlikerepo): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        if ($evenement->islikebyuser($user)) {
            $like = $evenementlikerepo->findOneBy([
                'event' => $evenement,
                'user' => $user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json(['code' => 200, 'message' => 'like bien supprimer', 'likes' => $evenementlikerepo->count([
                'event' => $evenement
            ])], 200);
        }
        $like = new EventLike();
        $like->setUser($user)
            ->setEvent($evenement);
        $entityManager->persist($like);
        $entityManager->flush();
        return $this->json(['code' => 200, 'message' => 'like ajouter', 'likes' => $evenementlikerepo->count([
            'event' => $evenement
        ])], 200);
    }

}
