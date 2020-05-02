<?php

namespace App\Controller;

use App\Entity\infoCommande;
use App\Form\InfoCommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/reservation")
 */
class infoCommandeController extends AbstractController
{
    /**
     * @Route("/", name="ligne_reservation_index", methods={"GET"})
     */
    public function index(): Response
    {
        $ligneReservations = $this->getDoctrine()
            ->getRepository(infoCommande::class)
            ->findAll();

        return $this->render('ligne_reservation/produit.html.twig', [
            'ligne_reservations' => $ligneReservations,
        ]);
    }

    /**
     * @Route("/new", name="ligne_reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneReservation = new infoCommande();
        $form = $this->createForm(InfoCommandeType::class, $ligneReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneReservation);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_reservation_index');
        }

        return $this->render('ligne_reservation/new.html.twig', [
            'ligne_reservation' => $ligneReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_reservation_show", methods={"GET"})
     */
    public function show(infoCommande $ligneReservation): Response
    {
        return $this->render('ligne_reservation/show.html.twig', [
            'ligne_reservation' => $ligneReservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, infoCommande $ligneReservation): Response
    {
        $form = $this->createForm(InfoCommandeType::class, $ligneReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_reservation_index');
        }

        return $this->render('ligne_reservation/edit.html.twig', [
            'ligne_reservation' => $ligneReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, infoCommande $ligneReservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneReservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneReservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_reservation_index');
    }
}
