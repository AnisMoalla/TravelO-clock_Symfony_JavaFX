<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Repository\GuideRepository;
use App\Repository\HotelRepository;
use App\Repository\OffreRepository;
use App\Repository\PromotionRepository;
use App\Repository\VendeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="fronthome", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository , OffreRepository $offreRepository , GuideRepository $guideRepository , VendeurRepository $vendeurRepository , HotelRepository $hotelRepository , PromotionRepository $promotionRepository): Response
    {
        $evenements = $evenementRepository->findAll();
        $offres = $offreRepository->findAll();
        $vendeurs = $vendeurRepository->findAll();
        $guides = $guideRepository->findAll();
        $hotels = $hotelRepository->findAll();
        $promotions = $promotionRepository->findAll();
        return $this->render('baseFront.html.twig',[
            'evenements' => $evenements,
            'offres' => $offres,
            'vendeurs' => $vendeurs,
            'guides' => $guides ,
            'hotels' => $hotels ,
            'promotions' => $promotions,
        ]);
    }
}
