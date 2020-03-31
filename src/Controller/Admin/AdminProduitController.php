<?php

namespace App\Controller\Admin;

use App\Entity\Associer;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProduitType;

class AdminProduitController extends AbstractController {

    /**
     * @var ProduitRepository
     */

    private $Prepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, ProduitRepository $Prepository)
    {
        $this->Prepository = $Prepository;
        $this->em = $em;
    }

    /**
     * @route("/adminProduit", name = "admin.produit.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $produits = $this->Prepository->findAll();
        return $this->render('admin/produit/produit.html.twig', compact('produits'));
    }

    /**
     * @Route("/adminProduit/create", name="admin.produit.new")
     * @param Produit $produit
     */

    public function new (Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($produit);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.produit.index');
        }
        return $this->render('admin/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminProduit/{id}", name="admin.produit.edit", methods="GET|POST")
     * @param Produit $produit
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Produit $produit, Request $request)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.produit.index');
        }

        return $this->render('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminProduit/{id}", name="admin.produit.delete", methods="DELETE")
     * @param Produit $produit
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Produit $produit, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $produit->getId(), $request->get('_token'))){
            $this->em->remove($produit);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.produit.index');
    }


}
