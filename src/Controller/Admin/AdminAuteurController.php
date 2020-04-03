<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Form\AuteurType;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuteurController extends AbstractController
{

    /**
     * @var AuteurRepository
     */

    private $Arepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, AuteurRepository $Arepository)
    {
        $this->Arepository = $Arepository;
        $this->em = $em;
    }

    /**
     * @route("/adminAuteur", name = "admin.auteur.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $auteurs = $this->Arepository->findAll();
        return $this->render('admin/auteur/index.html.twig', compact('auteurs'));
    }

    /**
     * @Route("/adminAuteur/create", name="admin.auteur.new")
     * @param Auteur $auteur
     */

    public function new (Request $request)
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($auteur);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.auteur.index');
        }
        return $this->render('admin/auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminAuteur/{id}", name="admin.auteur.edit", methods="GET|POST")
     * @param Auteur $auteur
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Auteur $auteur, Request $request)
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.auteur.index');
        }

        return $this->render('admin/auteur/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminAuteur/{id}", name="admin.auteur.delete", methods="DELETE")
     * @param Auteur $auteur
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Auteur $auteur, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $auteur->getId(), $request->get('_token'))){
            $this->em->remove($auteur);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.auteur.index');
    }
}