<?php

namespace App\Controller\Admin;

use App\Entity\optionMagasin;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUtilisateurController extends AbstractController
{

    /**
     * @var UtilisateurRepository
     */

    private $Urepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, UtilisateurRepository $Urepository)
    {
        $this->Urepository = $Urepository;
        $this->em = $em;
    }

    /**
     * @route("/adminUtilisateur", name = "admin.utilisateur.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $utilisateurs = $this->Urepository->findAll();
        return $this->render('admin/utilisateur/index.html.twig', compact('utilisateurs'));
    }

    /**
     * @Route("/adminUtilisateur/create", name="admin.utilisateur.new")
     * @param Utilisateur $utilisateur
     */

    public function new (Request $request)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($utilisateur);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.utilisateur.index');
        }
        return $this->render('admin/utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminUtilisateur/{id}", name="admin.utilisateur.edit", methods="GET|POST")
     * @param Utilisateur $utilisateur
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Utilisateur $utilisateur, Request $request)
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.utilisateur.index');
        }

        return $this->render('admin/utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminUtilisateur/{id}", name="admin.utilisateur.delete", methods="DELETE")
     * @param Utilisateur $utilisateur
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Utilisateur $utilisateur, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->get('_token'))){
            $this->em->remove($utilisateur);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.utilisateur.index');
    }
}