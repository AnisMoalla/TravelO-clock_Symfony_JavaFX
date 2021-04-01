<?php

namespace App\Controller;

use App\Entity\Badword;
use App\Form\BadwordType;
use App\Repository\BadwordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/badword")
 */
class BadwordController extends AbstractController
{
    /**
     * @Route("/", name="badword_index", methods={"GET"})
     */
    public function index(BadwordRepository $badwordRepository): Response
    {
        return $this->render('badword/index.html.twig', [
            'badwords' => $badwordRepository->findAll(),
        ]);
    }
    /**
     * @Route("/listword",name="jsonbad",methods={"GET"}  )
     * 
     */

    public function words(): Response
    {
        $badwordRepository = $this->getDoctrine()->getRepository(Badword::class);
        $badwords = $badwordRepository->findAll();
        $array = [];
        foreach ($badwords as $badword) {


            $array[] = [
                'id' => $badword->getId(),
                'content' => $badword->getWord()
            ];
        }
        $test2 = json_encode($array);


        return new Response($test2);
    }

    /**
     * @Route("/new", name="badword_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $badword = new Badword();
        $form = $this->createForm(BadwordType::class, $badword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($badword);
            $entityManager->flush();

            return $this->redirectToRoute('badword_index');
        }

        return $this->render('badword/new.html.twig', [
            'badword' => $badword,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="badword_show", methods={"GET"})
     */
    public function show(Badword $badword): Response
    {
        return $this->render('badword/show.html.twig', [
            'badword' => $badword,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="badword_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Badword $badword): Response
    {
        $form = $this->createForm(BadwordType::class, $badword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('badword_index');
        }

        return $this->render('badword/edit.html.twig', [
            'badword' => $badword,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="badword_delete", methods={"POST"})
     */
    public function delete(Request $request, Badword $badword): Response
    {
        if ($this->isCsrfTokenValid('delete' . $badword->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($badword);
            $entityManager->flush();
        }

        return $this->redirectToRoute('badword_index');
    }
}
