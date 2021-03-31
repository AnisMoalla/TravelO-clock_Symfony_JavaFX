<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Guide;
use App\Form\GuideType;
use App\Repository\GuideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank ;
use Symfony\Component\Form\Extension\Core\Type\FileType ;

class GuideController extends AbstractController
{
    /**
     * @Route("/guides", name="guides", methods={"GET"})
     */
    public function index(GuideRepository $guideRepository): Response
    {
        return $this->render('guide/index.html.twig', [
            'guides' => $guideRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newguide", name="guide_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
}
