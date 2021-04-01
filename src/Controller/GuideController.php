<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Guide;
use App\Entity\User;
use App\Form\GuideType;
use App\Repository\EvenementRepository;
use App\Repository\GuideRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\FileType ;
use Dompdf\Dompdf;
use Dompdf\Options;

class GuideController extends AbstractController
{
    /**
     * @Route("/guides", name="guides", methods={"GET"})
     */
    public function index(Request $request , PaginatorInterface $paginator): Response
    {
        $donnees=$this->getDoctrine()->getManager()->getRepository(Guide::class)->findAll();

        $guides = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            1
        );

        return $this->render('guide/index.html.twig', [
            'guides' => $guides,
        ]);
    }

    /**
     * @Route("/newguide", name="guide_new", methods={"GET","POST"})
     */
    public function new(Request $request,SessionInterface $session): Response
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] != "ROLE_ADMIN")
        {
            return $this->redirectToRoute("fronthome");
        }

        $guide = new Guide();
        $form = $this->createForm(GuideType::class, $guide)
        ->add('photo', FileType::class, [
          'label' => 'Profile picture',

          'mapped' => false,

          'required' => false,

          'constraints' => [
            new Image(),
            new NotBlank()
          ]])
        ->add('ajouter', SubmitType::class);
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
           $guide->setImage($newFilename);
          }
            $userRep = $this->getDoctrine()->getRepository(User::class);
            $user = $userRep->find($session->get('user'));
            $guide->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($guide);
            $entityManager->flush();

            return $this->redirectToRoute('guides');
        }

        return $this->render('guide/new.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/guide/{id}", name="guide_show", methods={"GET"})
     */
    public function show(Guide $guide): Response
    {
        return $this->render('guide/show.html.twig', [
            'guide' => $guide,
        ]);
    }

    /**
     * @Route("/{id}/editguide", name="guide_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Guide $guide): Response
    {
        $form = $this->createForm(GuideType::class, $guide)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('guides');
        }

        return $this->render('guide/edit.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deleteguide", name="guide_delete")
     */
    public function delete(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $guideRepository = $this->getDoctrine()->getRepository(Guide::class);
        $guide = $guideRepository->find($id);
        $entityManager->remove($guide);
        $entityManager->flush();
        return $this->redirectToRoute('guides');
    }

    /**
     * @Route("/frontGuides", name="frontGuides", methods={"GET"})
     */
    public function frontGuides(GuideRepository $guideRepository): Response
    {
        return $this->render('guide/frontguides.html.twig', [
            'guides' => $guideRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/frontGuide", name="frontGuide", methods={"GET"})
     */
    public function frontGuide(GuideRepository $guideRepository , $id): Response
    {
        return $this->render('guide/frontguide.html.twig', [
            'guides' => $guideRepository->findAll(),
            'guide' => $guideRepository->find($id),
        ]);
    }

    /**
     * @Route("/newguidefront", name="new_guide", methods={"GET","POST"})
     */
    public function newf(Request $request): Response
    {
        $guide = new Guide();
        $form = $this->createForm(GuideType::class, $guide)
            ->add('photo', FileType::class, [
                'label' => 'Profile picture',

                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new Image(),
                    new NotBlank()
                ]])
            ->add('ajouter', SubmitType::class);
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
                $guide->setImage($newFilename);
            }
            $guide->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($guide);
            $entityManager->flush();

            return $this->redirectToRoute('frontGuides');
        }

        return $this->render('guide/newfront.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editguidefront", name="edit_guide", methods={"GET","POST"})
     */
    public function editf(Request $request, Guide $guide): Response
    {
        $form = $this->createForm(GuideType::class, $guide)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('frontGuides');
        }

        return $this->render('guide/editfront.html.twig', [
            'guide' => $guide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletefrontguide", name="delete_guide")
     */
    public function deletef(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $guideRepository = $this->getDoctrine()->getRepository(Guide::class);
        $guide = $guideRepository->find($id);
        $entityManager->remove($guide);
        $entityManager->flush();
        return $this->redirectToRoute('frontGuides');
    }

    /**
     * @Route("/guides/json", name="guidesjson", methods={"GET"})
     */
    public function indexjson(GuideRepository $guideRepository): Response
    {
        $guides = $guideRepository->findAll();

        foreach ($guides as $guide) {
            $raw[] = [
                'id' => $guide->getId(),
                'nom' => $guide->getNom(),
                'prenom' => $guide->getPrenom()
            ];
        }
        $data = json_encode($raw);


        return new Response($data);
    }

    /**
     * @Route("/mailguide", name="mailguide", methods={"GET","POST"})
     */
    public function mailguide(\Swift_Mailer $mailer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');


        $message = (new \Swift_Message('Mail Admin Guides'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                "Mail Guides"
            )
        ;

        $mailer->send($message);
        return $this->redirectToRoute("guides");
    }

    /**
     * @Route("/guidesliste", name="guidesliste", methods={"GET"})
     */
    public function print(GuideRepository $guideRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('guide/guidesliste.html.twig', [
            'title' => "Welcome to our PDF Test",
            'guides' => $guideRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Guidespdf.pdf", [
            "Attachment" => true

        ]);
    }/**
 * @Route("/rateGuide", name="rateGuide", methods={"POST"})
 */
    public function rateAction(Request $request){
        $data = $request->getContent();
        $obj = json_decode($data,true);

        $em = $this->getDoctrine()->getManager();
        $rate =$obj['rate'];
        $id = $obj['guide'];
        $guide = $em->getRepository(Evenement::class)->find($id);
        $note = ($guide->getRate()*$guide->getVote() + $rate)/($guide->getVote()+1);
        $guide->setVote($guide->getVote()+1);
        $guide->setRate($note);
        $em->persist($guide);
        $em->flush();
        return new Response($guide->getRate());
    }


}
