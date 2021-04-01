<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Promotion;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use App\Repository\ReclamationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class PromotionController extends AbstractController
{
    /**
     * @Route("/promotion", name="promotion_index", methods={"GET"})
     */
    public function index(PromotionRepository $promotionRepository): Response
    {

        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/newpromotion", name="promotion_new", methods={"GET","POST"})
     */
    public function new(Request $request,$id,SessionInterface $session): Response
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }

        if ($session->get("user")->getRoles()[0] == "ROLE_TOURIST")
        {
            return $this->redirectToRoute("fronthome");
        }
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion)
            ->add("ajouter", SubmitType::class);
        $hotelRep = $this->getDoctrine()->getRepository(Hotel::class);
        $hotel = $hotelRep->find($id);
        if ($hotel == null) {
            return $this->redirectToRoute('fronthome');
        } else {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $userRep = $this->getDoctrine()->getRepository(User::class);
                $user = $userRep->find($session->get('user'));
                $promotion->setHotel($hotel);
                $promotion->setUser($user);
                    if (($promotion->getDateDebut() < new \DateTime()) or (($promotion->getDateDebut() > $promotion->getDateFin()))) {
                    return $this->render('promotion/new.html.twig', [
                        'promotion' => $promotion,
                        'form' => $form->createView(),
                        'check' => true
                    ]);
                } else {

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($promotion);
                    $entityManager->flush();

                    return $this->redirectToRoute('listerpro');
                }
            }
            return $this->render('promotion/new.html.twig', [
                'promotion' => $promotion,
                'form' => $form->createView(),
            ]);

        }
    }

    /**
     * @Route("/{id}/showpromotion", name="promotion_show", methods={"GET"})
     */
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    /**
     * @Route("/{id}/editpromotion", name="promotion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Promotion $promotion): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion)
            ->add("modifier",SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($promotion->getDateDebut() < new \DateTime()) or (($promotion->getDateDebut() > $promotion->getDateFin()))) {
                return $this->render('promotion/edit.html.twig', [
                    'promotion' => $promotion,
                    'form' => $form->createView(),
                    'check' => true
                ]);
            } else {

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('listerpro');
            }}

        return $this->render('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/deletepromotion", name="promotion_delete")
     */
    public function delete(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $promoRepository = $this->getDoctrine()->getRepository(Promotion::class);
        $promotion = $promoRepository->find($id);
        $entityManager->remove($promotion);
        $entityManager->flush();
        return $this->redirectToRoute('listerpro');
    }

    /**
     * @Route("/{id}/deletepromotionback", name="promotion_deleteback")
     */
    public function deleteback(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $promoRepository = $this->getDoctrine()->getRepository(Promotion::class);
        $promotion = $promoRepository->find($id);
        $entityManager->remove($promotion);
        $entityManager->flush();
        return $this->redirectToRoute('promotion_index');
    }


    /**
     * @Route("/listerpro", name="listerpro", methods={"GET"})
     */
    public function lister(PromotionRepository $promotionRepository,PaginatorInterface $paginator,Request $request, SessionInterface $session)
    {
        if (!$session->has("user"))
        {
            return $this->redirectToRoute("login");
        }
        $userRep = $this->getDoctrine()->getRepository(User::class);
        $user = $userRep->find($session->get('user'));
        $promotion = $promotionRepository->findByUser($user);
        $evenements = $paginator->paginate(
            $promotion ,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('/promotion/mespromo.html.twig',
            ['listepro' => $evenements]);


    }


    public function getStatDate($data)
    {
        $res = array(0,0,0,0,0,0,0,0,0,0,0,0) ;
        foreach ($data as $r)
        {

            $index = $r->getDateDebut()->format('m') ;
            if ((int)$index >= 10)
                $index = $r->getDateDebut()->format('m') - 1 ;
            else
                $index = $r->getDateDebut()->format('m')[1] - 1 ;
            $res[$index]++ ;
        }

        return $res ;
    }


    /**
     * @Route("/statpro", name="statpro", methods={"GET"})
     */
    public function stat(PromotionRepository $promotionRepository)
    {

        $data=$promotionRepository->findAll();
        $res = $this->getStatDate($data) ;

        return $this->render('/promotion/statpro.html.twig' ,['res'=>$res]

        );
    }
}