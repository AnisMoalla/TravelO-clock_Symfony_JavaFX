<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\OffreType;
use App\Repository\EvenementRepository;
use App\Repository\OffreRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class OffreController extends AbstractController
{

    /**
     * @Route("/offresliste", name="offresliste", methods={"GET"})
     */
    public function print(OffreRepository $offreRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('offre/offresliste.html.twig', [
            'title' => "Welcome to our PDF Test",
            'offres' => $offreRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Offrespdf.pdf", [
            "Attachment" => true

        ]);
    }

    /**
     * @Route("/mailoffre", name="mailoffre", methods={"GET","POST"})
     */
    public function mailoffre(\Swift_Mailer $mailer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');


        $message = (new \Swift_Message('Welcome to our website'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                "voila nos derniers offres"
            )
        ;

        $mailer->send($message);
        return $this->redirectToRoute("offres");
    }

    /**
     * @Route("/offre/{id}", name="offre_show", methods={"GET"})
     */
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/offres", name="offres", methods={"GET"})
     */
    public function index(Request $request , PaginatorInterface $paginator): Response
    {
        $donnees=$this->getDoctrine()->getManager()->getRepository(Offre::class)->findAll();

        $offres = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    /**
     * @Route("/newoffre", name="offre_new", methods={"GET","POST"})
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

        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre)->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRep = $this->getDoctrine()->getRepository(User::class);
            $user = $userRep->find($session->get('user'));
            $offre->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('offres');
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editoffre", name="offre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offres');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deleteoffre", name="offre_delete")
     */
    public function delete(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offreRepository = $this->getDoctrine()->getRepository(Offre::class);
        $offre = $offreRepository->find($id);
        $entityManager->remove($offre);
        $entityManager->flush();
        return $this->redirectToRoute('offres');
    }

    /**
     * @Route("/frontOffres", name="frontOffres", methods={"GET"})
     */
    public function frontEvenement(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/frontoffres.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newoffrefront", name="new_offre", methods={"GET","POST"})
     */
    public function newf(Request $request , SessionInterface $session): Response
    {
        $id = $request->get('id');
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre)->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offre->setUser($this->getUser());
            $offre->setEvenement($evenement);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('frontOffres');
        }


        return $this->render('offre/newfront.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editoffrefront", name="edit_offre", methods={"GET","POST"})
     */
    public function editf(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreType::class, $offre)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('frontOffres');
        }

        return $this->render('offre/editfront.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deleteoffrefront", name="delete_offre")
     */
    public function deletef(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offreRepository = $this->getDoctrine()->getRepository(Offre::class);
        $offre = $offreRepository->find($id);
        $entityManager->remove($offre);
        $entityManager->flush();
        return $this->redirectToRoute('frontOffres');
    }

    /**
     * @Route("/frontmesOffres", name="frontmesOffres", methods={"GET"})
     */
    public function frontmesEvenement(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/frontmesoffres.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/frontOffrea", name="frontOffrea", methods={"GET"})
     */
    public function frontEvenementa(OffreRepository $offreRepository , $id): Response
    {
        return $this->render('offre/frontoffre.html.twig', [
            'offres' => $offreRepository->findAll(),
            'offre' => $offreRepository->find($id),
        ]);
    }


}
