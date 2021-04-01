<?php

namespace App\Controller;

use App\Entity\Facceuil;
use App\Entity\Notification;
use App\Entity\ReclamationFacc;
use App\Entity\User;
use App\Form\FacceuilType;
use App\Repository\FacceuilRepository;
use App\Repository\ReservationFacceuilRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facceuil")
 */
class FacceuilController extends AbstractController
{
    /**
     * @Route("/", name="facceuil_index", methods={"GET"})
     */
    public function index(FacceuilRepository $facceuilRepository): Response
    {
        return $this->render('facceuil/index.html.twig', [
            'facceuils' => $facceuilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/details/{id}", name="details_show", methods={"GET"})
     */
    public function detailsMaison(FacceuilRepository $facceuilRepository,$id,ReservationFacceuilRepository $reservationFacceuilRepository): Response
    {
        $User = $this->getDoctrine()->getRepository(User::class)->find(1);

        return $this->render('facceuil/detailsMaiosn.html.twig', [
            'facceuil' => $facceuilRepository->find(array('id'=>$id)),
            'reservation' => $reservationFacceuilRepository->findAll(),
            'user'=>$User

        ]);
    }

    /**
         * @Route("/indexAdmin", name="facceuil_admin", methods={"GET"})
     */
    public function indexAdmin(FacceuilRepository $facceuilRepository): Response
    {
      //  $User = $this->getDoctrine()->getRepository(User::class)->find(1);
        //$nom = $User->getNom();
        return $this->render('facceuil/indexAdmin.html.twig', [
            'facceuils' => $facceuilRepository->findAll(),

        ]);
    }
    /**
     * @Route("/frontFamille", name="frontFamille", methods={"GET"})
     */
    public function frontFamille(FacceuilRepository $facceuilRepository): Response
    {
        return $this->render('facceuil/FamilleFront.html.twig', [
            'facceuils' => $facceuilRepository->findAll(),

        ]);
    }

    /**
     * @Route("/frontFamille/{id}", name="frontFamille", methods={"GET"})
     */
    public function frontFamilleByUser(FacceuilRepository $facceuilRepository,$id): Response
    {
        $id=1;
        return $this->render('facceuil/show.html.twig', [
            'facceuils' => $facceuilRepository->findBy(array('user'=>$id)),
        ]);
    }

    /**
     * @Route("/new", name="facceuil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $facceuil = new Facceuil();
        $form = $this->createForm(FacceuilType::class, $facceuil)->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $facceuil->setImage("3.jpg");
            $facceuil->getUploadFile();
            $User = $this->getDoctrine()->getRepository(User::class)->find(1);
            $idU=$User->getId();
            $facceuil->setEtat("disable");
            $facceuil->setUser($User);
            $entityManager->persist($facceuil);
            $entityManager->flush();

            return $this->redirectToRoute('frontFamille',['id' => $idU]);
        }

        return $this->render('facceuil/new.html.twig', [
            'facceuil' => $facceuil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="facceuil_show", methods={"GET"})
     */
    public function show(Facceuil $facceuil): Response
    {
        return $this->render('facceuil/show.html.twig', [
            'facceuil' => $facceuil,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="facceuil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Facceuil $facceuil): Response
    {
        $form = $this->createForm(FacceuilType::class, $facceuil)->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        $idU =1;
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('frontFamille',['id' => $idU]);
        }

        return $this->render('facceuil/edit.html.twig', [
            'facceuil' => $facceuil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="facceuil_delete")
     */
    public function delete(Request $request, Facceuil $facceuil): Response
    {
        $reclamationfac = $this->getDoctrine()->getRepository(ReclamationFacc::class);
        $notification = $this->getDoctrine()->getRepository(Notification::class);
        $rec = $reclamationfac->findByFacceuil($facceuil) ;
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($rec as $r)
        {
            $nots = $notification->findByReclamationFamille($r);
            foreach ($nots as $n)
            {
                $entityManager->remove($n);
            }


            $entityManager->remove($r);

        }
        $entityManager->remove($facceuil);


        $entityManager->flush();


        return $this->redirectToRoute('frontFamille',['id' => 1]);
    }



    /**
     * @Route("/enable/{id}", name="enable", methods={"GET"})
     */
    public function enableMaison(FacceuilRepository $facceuilRepository,$id,\Swift_Mailer $mailer): Response
    {
         $maison = $this->getDoctrine()->getRepository(Facceuil::class)->find($id);
         $maison->setEtat('enable');
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($maison);
         $entityManager->flush();


        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($maison->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'facceuil/emailEnable.html.twig',
                    ['maison' => $maison ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('facceuil_admin');

    }

    /**
     * @Route("/disable/{id}", name="disable", methods={"GET"})
     */
    public function disableMaison(FacceuilRepository $facceuilRepository,$id,\Swift_Mailer $mailer): Response
    {
        $maison = $this->getDoctrine()->getRepository(Facceuil::class)->find($id);
        $maison->setEtat('disable');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($maison);
        $entityManager->flush();
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('projetpidev992@gmail.com')
            ->setTo($maison->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'facceuil/emailsDesaible.html.twig',
                    ['maison' => $maison ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('facceuil_admin');

    }

    /**
     * @Route("/rate", name="rate_", methods={"POST"})
     */
    public function rateAction(\Symfony\Component\HttpFoundation\Request $request){
        $data = $request->getContent();
        $obj = json_decode($data,true);

        $em = $this->getDoctrine()->getManager();
        $rate =$obj['rate'];
        $idc = $obj['facceuil'];
        $facceuil = $em->getRepository(Facceuil::class)->find($idc);
        $note = ($facceuil->getRate()*$facceuil->getVote() + $rate)/($facceuil->getVote()+1);
        $facceuil->setVote($facceuil->getVote()+1);
        $facceuil->setRate($note);
        $em->persist($facceuil);
        $em->flush();
        return new Response($facceuil->getRate());
    }

}
