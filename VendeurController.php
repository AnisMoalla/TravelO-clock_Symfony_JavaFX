<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Vendeur;
use App\Form\VendeurType;
use App\Repository\EvenementRepository;
use App\Repository\VendeurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\FileType ;

class VendeurController extends AbstractController
{
    /**
     * @Route("/vendeurs", name="vendeurs", methods={"GET"})
     */
    public function index(Request $request , PaginatorInterface $paginator): Response
    {
        $donnees=$this->getDoctrine()->getManager()->getRepository(Vendeur::class)->findAll();

        $vendeurs = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('vendeur/index.html.twig', [
            'vendeurs' => $vendeurs,
        ]);
    }

    /**
     * @Route("/newvendeur", name="vendeur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vendeur = new Vendeur();
        $form = $this->createForm(VendeurType::class, $vendeur)
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
           $vendeur->setImage($newFilename);
          }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vendeur);
            $entityManager->flush();

            return $this->redirectToRoute('vendeurs');
        }

        return $this->render('vendeur/new.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vendeur/{id}", name="vendeur_show", methods={"GET"})
     */
    public function show(Vendeur $vendeur): Response
    {
        return $this->render('vendeur/show.html.twig', [
            'vendeur' => $vendeur,
        ]);
    }

    /**
     * @Route("/{id}/editvendeur", name="vendeur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vendeur $vendeur): Response
    {
        $form = $this->createForm(VendeurType::class, $vendeur)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vendeurs');
        }

        return $this->render('vendeur/edit.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deldetevendeur", name="vendeur_delete")
     */
    public function delete(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $vendeurRepository = $this->getDoctrine()->getRepository(Vendeur::class);
        $vendeur = $vendeurRepository->find($id);
        $entityManager->remove($vendeur);
        $entityManager->flush();
        return $this->redirectToRoute('vendeurs');
    }

    /**
     * @Route("/frontVendeurs", name="frontVendeurs", methods={"GET"})
     */
    public function frontVendeur(VendeurRepository $vendeurRepository , PaginatorInterface $paginator , Request $request): Response
    {
        $donnees=$this->getDoctrine()->getManager()->getRepository(Vendeur::class)->findAll();

        $vendeurs = $paginator->paginate(
            $donnees ,
            $request->query->getInt('page',1),
            1
        );

        return $this->render('vendeur/frontvendeurs.html.twig', [
            'vendeurs' => $vendeurs,
        ]);
    }

    /**
     * @Route("/{id}/frontVendeur", name="frontVendeur", methods={"GET"})
     */
    public function frontVendeu(VendeurRepository $vendeurRepository , $id): Response
    {
        return $this->render('vendeur/frontvendeur.html.twig', [
            'vendeurs' => $vendeurRepository->findAll(),
            'vendeur' => $vendeurRepository->find($id),
        ]);
    }

    /**
     * @Route("/newfrontvendeur", name="new_vendeur", methods={"GET","POST"})
     */
    public function newf(Request $request): Response
    {
        $vendeur = new Vendeur();
        $form = $this->createForm(VendeurType::class, $vendeur)
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
                $vendeur->setImage($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vendeur);
            $entityManager->flush();

            return $this->redirectToRoute('frontVendeurs');
        }

        return $this->render('vendeur/newfront.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editfrontvendeur", name="edit_vendeur", methods={"GET","POST"})
     */
    public function editf(Request $request, Vendeur $vendeur): Response
    {
        $form = $this->createForm(VendeurType::class, $vendeur)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('frontVendeurs');
        }

        return $this->render('vendeur/editfront.html.twig', [
            'vendeur' => $vendeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deldetefrontvendeur", name="delete_vendeur")
     */
    public function deletef(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $vendeurRepository = $this->getDoctrine()->getRepository(Vendeur::class);
        $vendeur = $vendeurRepository->find($id);
        $entityManager->remove($vendeur);
        $entityManager->flush();
        return $this->redirectToRoute('frontVendeurs');
    }

    /**
     * @Route("/rateVendeur", name="rateVendeur", methods={"POST"})
     */
    public function rateAction(Request $request){
        $data = $request->getContent();
        $obj = json_decode($data,true);

        $em = $this->getDoctrine()->getManager();
        $rate =$obj['rate'];
        $id = $obj['vendeur'];
        $vendeur = $em->getRepository(Vendeur::class)->find($id);
        $note = ($vendeur->getRate()*$vendeur->getVote() + $rate)/($vendeur->getVote()+1);
        $vendeur->setVote($vendeur->getVote()+1);
        $vendeur->setRate($note);
        $em->persist($vendeur);
        $em->flush();
        return new Response($vendeur->getRate());
    }
}
