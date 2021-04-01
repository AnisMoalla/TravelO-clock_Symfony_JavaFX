<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\OffreRepository;
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
     * @Route("/{id}/newreservationfront", name="new_reservation", methods={"GET","POST"})
     */
    public function newf(Request $request , EvenementRepository $evenementRepository , $id): Response
    {
        $reservation = new Reservation();
        $entityManager = $this->getDoctrine()->getManager();
        $event = $evenementRepository->find($id);
        $userrep=$this->getDoctrine()->getRepository(User::class);
        $user=$userrep->find($this->getUser()->getId());
        $tarif = $event->getPrix();
        $reservation->setEvenement($event);
        $reservation->setTourist($user);
        $reservation->setTarif($tarif);
        $reservation->setDateReservation(new \DateTime());
        $form = $this->createForm(ReservationType::class, $reservation)
            ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $event->setNbrPlaces($event->getNbrPlaces() - 1);
            $entityManager->persist($reservation);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('frontmesReservations');
        }

        return $this->render('reservation/frontreservation.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'event' => $evenementRepository->find($id),
        ]);
    }

    /**
     * @Route("/{id}/newreservationofront", name="new_reservationo", methods={"GET","POST"})
     */
    public function newfn(Request $request , OffreRepository $offreRepository , $id , EvenementRepository $evenementRepository): Response
    {
        $reservation = new Reservation();
        $entityManager = $this->getDoctrine()->getManager();
        $offre = $offreRepository->find($id);
        $event = $offre->getEvenement();
        $userrep=$this->getDoctrine()->getRepository(User::class);
        $user=$userrep->find($this->getUser()->getId());
        $tarif = ($event->getPrix() / 100 ) * $offre->getPourcentage();
        $reservation->setEvenement($event);
        $reservation->setOffre($offre);
        $reservation->setTourist($user);
        $reservation->setDateReservation(new \DateTime());
        $reservation->setTarif($tarif);
        $form = $this->createForm(ReservationType::class, $reservation)
            ->add('ajouter',SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $event = $offreRepository->find($id)->getEvenement();
            $event->setNbrPlaces($event->getNbrPlaces() - 1);
            $entityManager->persist($reservation);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('frontmesReservations');
        }

        return $this->render('reservation/frontreservationo.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'offre' => $offreRepository->find($id),
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
        $this->get('session')->getFlashBag()->add('notice','Reservation Supprimé');
        return $this->redirectToRoute('frontmesReservations');
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