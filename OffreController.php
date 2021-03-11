<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Offre;
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
use Symfony\Component\Routing\Annotation\Route;


class OffreController extends AbstractController
{
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
     * @Route("/newoffre", name="offre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre)->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/offre/{id}", name="offre_show", methods={"GET"})
     */
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
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
    public function newf(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre)->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
}
