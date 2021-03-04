<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hotel")
 */
class HotelController extends AbstractController
{
    /**
     * @Route("/afficheHotel", name="afficheHotel", methods={"GET"})
     */
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }


    /**
     * @Route("/frontHotel", name="frontHotel", methods={"GET"})
     */
    public function frontHotel(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/HotelFront.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hotel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $hotel->setImage("3.jpg");
            $hotel->getUploadFile();
            $entityManager->persist($hotel);
            $entityManager->flush();

            return $this->redirectToRoute('afficheHotel');
        }

        return $this->render('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hotel_show", methods={"GET"})
     */
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hotel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hotel $hotel): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('afficheHotel');
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="hotel_delete")
     */
    public function delete($id, HotelRepository $repository)
    {

            $hotel =$repository->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hotel);
            $entityManager->flush();
            return $this->redirectToRoute('afficheHotel');

    }
}
