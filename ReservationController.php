<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ReservationController extends AbstractController
{
    /**
     * @Route("/reservations", name="reservations", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newreservation", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation)
        ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservations');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reservation/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/editreservation", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservations');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletereservation", name="reservation_delete")
     */
    public function delete(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservationRepository = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $reservationRepository->find($id);
        $entityManager->remove($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('reservations');
    }

    /**
     * @Route("/frontReservations", name="frontReservations", methods={"GET"})
     */
    public function frontReservation(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/frontreservations.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newreservationfront", name="new_reservation", methods={"GET","POST"})
     */
    public function newf(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation)
            ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('frontEvenements');
        }

        return $this->render('reservation/frontreservation.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editreservationfront", name="edit_reservation", methods={"GET","POST"})
     */
    public function editf(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation)->add('modifier', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservations');
        }

        return $this->render('reservation/editfront.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletereservationfront", name="delete_reservation")
     */
    public function deletef(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservationRepository = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $reservationRepository->find($id);
        $entityManager->remove($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('frontReservations');
        $this->get('session')->getFlashBag()->add('notice','Reservation Supprimé');
    }

    /**
     * @Route("/frontmesReservations", name="frontmesReservations", methods={"GET"})
     */
    public function frontmesReservation(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/frontmesreservations.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }


}
