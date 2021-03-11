<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\User;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf;
use Dompdf\Options;



class EvenementController extends AbstractController
{
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
        $pieChart->getOptions()->setTitle('OFFRES DES EVENEMENTS!');
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
     * @Route("/newevenement", name="evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
     * @Route("/evenement/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
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
        $entityManager->remove($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenements');
    }

    /**
     * @Route("/frontEvenements", name="frontEvenements", methods={"GET"})
     */
    public function frontEvenement(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/frontevents.html.twig', [
            'evenements' => $evenementRepository->findAll(),
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('frontEvenements');
        }

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

            return $this->redirectToRoute('evenements');
        }

        return $this->render('evenement/editfront.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
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


        $message = (new \Swift_Message('Welcome to our website'))
                ->setFrom('projetpidev992@gmail.com')
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    "hey"
                )
            ;

            $mailer->send($message);
            return $this->redirectToRoute("evenements");
    }
}
