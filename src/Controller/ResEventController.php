<?php

namespace App\Controller;

use App\Entity\Calander;
use App\Entity\Guide;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ResEventController extends AbstractController
{
    /**
     * @Route("/res/event", name="res_event")
     */
    public function index(): Response
    {
        return $this->render('res_event/index.html.twig', [
            'controller_name' => 'ResEventController',
        ]);
    }


    /**
     * @Route("/res/modif", name="eventResModif")
     */

    public function modifier(Request $request): Response
    {
        $guideRep = $this->getDoctrine()->getRepository(Guide::class);

        $entityManager = $this->getDoctrine()->getManager();
        $calan = $this->getDoctrine()->getRepository(Calander::class);
        $calander = $calan->find($request->get('id'));
        $calander->setTitle($request->get('title'));
        $calander->setDateBegin(new DateTime($request->get('date_debut')));

        $calander->setDataFin(new DateTime($request->get('date_fin')));
        $guide = new Guide();
        $guide = $guideRep->find($request->get('guide'));

        $calander->setGuide($guide);
        $entityManager->persist($calander);
        $entityManager->flush($calander);





        return $this->json(['message' => $calander->getTitle()], 200);
    }

    /**
     * @Route("/res/del", name="eventResModif")
     */

    public function delete(Request $request): Response
    {
        $guideRep = $this->getDoctrine()->getRepository(Guide::class);

        $entityManager = $this->getDoctrine()->getManager();
        $calan = $this->getDoctrine()->getRepository(Calander::class);
        $calander = $calan->find($request->get('id'));
        $entityManager->remove($calander);
        $entityManager->flush();





        return $this->json(['message' => $calander->getTitle()], 200);
    }
}
