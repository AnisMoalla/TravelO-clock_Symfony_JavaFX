<?php

namespace App\Controller;

use App\Entity\Facceuil;
use App\Entity\ReservationFacceuil;
use App\Entity\User;
use App\Form\ReservationFacceuilType;
use App\Repository\ReservationFacceuilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation/facceuil")
 */
class ReservationFacceuilController extends AbstractController
{
    /**
     * @Route("/affiche", name="reservationFacceuil", methods={"GET"})
     */
    public function index(ReservationFacceuilRepository $reservationFacceuilRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find(1);
        return $this->render('reservation_facceuil/index.html.twig', [
            'reservation' => $reservationFacceuilRepository->findAll(),
            'user'=>$user,
        ]);
    }

    /**
     * @Route("/new/{id}", name="reservation_facceuil_new", methods={"GET","POST"})
     */
    public function new(Request $request, $id): Response
    {
        $reservationFacceuil = new ReservationFacceuil();

        $User = $this->getDoctrine()->getRepository(User::class)->find(1);
        $facceuil = $this->getDoctrine()->getRepository(Facceuil::class)->find($id);
        $reservationFacceuil->setUser($User);
        $reservationFacceuil->setFacceuil($facceuil);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservationFacceuil);
        $entityManager->flush();

        return $this->redirectToRoute('details_show', ['id' => $id]);


    }

    /**
     * @Route("/{id}", name="reservation_facceuil_show", methods={"GET"})
     */
    public function show(ReservationFacceuil $reservationFacceuil): Response
    {
        return $this->render('reservation_facceuil/show.html.twig', [
            'reservation_facceuil' => $reservationFacceuil,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_facceuil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReservationFacceuil $reservationFacceuil): Response
    {
        $form = $this->createForm(ReservationFacceuilType::class, $reservationFacceuil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_facceuil_index');
        }

        return $this->render('reservation_facceuil/edit.html.twig', [
            'reservation_facceuil' => $reservationFacceuil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/{idf}", name="reservation_facceuil_delete")
     */
    public function delete($idf, ReservationFacceuilRepository $repository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $User = $this->getDoctrine()->getRepository(User::class)->find(1);
        $reservation = $repository->findOneBy(array('user'=>$User->getId(),'facceuil'=>$idf));
        $entityManager->remove($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('details_show', ['id' => $idf]);
    }


    /**
     * @Route("/{id}/accepter", name="reservation_accepter")
     */
    public function AccepterMaison(Request $request, ReservationFacceuil $facceuil,\Swift_Mailer $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $maison = $this->getDoctrine()->getRepository(Facceuil::class)->find($facceuil->getFacceuil()->getId());
        $maison->setNbPlace($maison->getNbPlace()-1);
        $entityManager->persist($maison);
        $entityManager->remove($facceuil);
        $entityManager->flush();
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($facceuil->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'reservation_facceuil/EmailAcceptation.html.twig',
                    ['maison' => $facceuil ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('reservationFacceuil');

    }


    /**
     * @Route("/{id}/refuser", name="reser_refuser")
     */
    public function RefuserMaison(Request $request, ReservationFacceuil $facceuil,\Swift_Mailer $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($facceuil);
        $entityManager->flush();
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($facceuil->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'reservation_facceuil/EmailRefus.html.twig',
                    ['maison' => $facceuil ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('reservationFacceuil');

    }

}
