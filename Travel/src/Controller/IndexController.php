<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/admin", name="index")
     */
    public function index(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

 /**
     * @Route("/", name="home")
     */


    public function home(): Response
    {
        return $this->render('baseFront.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }


}
