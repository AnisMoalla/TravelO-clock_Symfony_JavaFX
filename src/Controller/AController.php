<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AController extends AbstractController
{
    /**
     * @Route("/a", name="a")
     */
    public function index(): Response
    {
        return $this->render('a/index.html.twig', [
            'controller_name' => 'AController',
        ]);
    }
}
