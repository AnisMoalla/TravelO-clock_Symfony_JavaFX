<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Promotion;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use phpDocumentor\Reflection\Types\True_;
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
        /*return $this->render('hotel/HotelFront.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);*/
        $Repromo = $this->getDoctrine()->getRepository(Promotion::class);
        $data = $Repromo->getAllPromotion();
        $res = array();
        foreach ($data as $d)
        {
            $hotel = $hotelRepository->find($d->getHotel()->getId());

            array_push($res,[$hotel , $d->getPourcentage()]);

        }
        $hotels =$hotelRepository->findAll();
        foreach ($hotels as $h)
        {
            if (!$this->check($res,$h))
            {
                array_push($res,[$h,0]);
            }
        }
        return $this->render('hotel/HotelFront.html.twig', [
            'hotels' => $res
        ]);

    }
    public function check($hotels,$hotel)
    {
        foreach ($hotels as $h)
        {
            if ($h[0]->getId()== $hotel->getId())
                return true ;
        }
        return false ;
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
