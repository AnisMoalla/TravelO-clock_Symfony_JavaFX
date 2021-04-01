<?php

namespace App\Controller;

use App\Entity\Calander;
use App\Entity\Guide;
use App\Entity\Plan;
use App\Form\PlanType;
use App\Repository\CalanderRepository;
use App\Repository\GuideRepository;
use App\Repository\PlanRepository;
use DateTime;
use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




/**
 * @Route("/plan")
 */
class PlanController extends AbstractController
{
    /**
     * @Route("/", name="plan_index", methods={"GET"})
     */
    public function index(CalanderRepository $calander): Response
    {
        $events = $calander->findAll();
$rdvs = [];

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getDateBegin()->format('Y-m-d H:i:s'),
                'fin' => $event->getDataFin()->format('Y-m-d H:i:s'),
                'guide' => $event->getGuide()->getNom()

            ];
        }
        $data = json_encode($rdvs);

        return $this->render('plan/index.html.twig', compact('data'));
    }

    /**
     * @Route("/new", name="plan_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plan = new Plan();
        $form = $this->createForm(PlanType::class, $plan)->add("save", SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('plan_index');
        }

        return $this->render('plan/new.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="plan_show", methods={"GET"})
     */
    public function show(Plan $plan): Response
    {
        return $this->render('plan/show.html.twig', [
            'plan' => $plan,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="plan_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plan $plan): Response
    {
        $form = $this->createForm(PlanType::class, $plan)->add("save", SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plan_index');
        }

        return $this->render('plan/edit.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletePlan", name="plan_delete",)
     */
    public function delete(Request $request, $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $planRepository = $this->getDoctrine()->getRepository(Plan::class);
        $plan = $planRepository->find($id);
        $entityManager->remove($plan);
        $entityManager->flush();


        return $this->redirectToRoute('plan_index');
    }


    /**
     * @Route("/newRes/" , name="addRes", methods={"POST"})
     * 
     */

    public function ajouterUnres(Request $request): Response
    {
        $guideRep = $this->getDoctrine()->getRepository(Guide::class);

        $entityManager = $this->getDoctrine()->getManager();
        $calanderRepository = $this->getDoctrine()->getRepository(Calander::class);
        $calander = new Calander();
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
}
