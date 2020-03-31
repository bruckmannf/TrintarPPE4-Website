<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategorieController extends AbstractController
{

    /**
     * @var CategorieRepository
     */

    private $Crepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, CategorieRepository $Crepository)
    {
        $this->Crepository = $Crepository;
        $this->em = $em;
    }

    /**
     * @route("/adminCategorie", name = "admin.categorie.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $categories = $this->Crepository->findAll();
        return $this->render('admin/categorie/index.html.twig', compact('categories'));
    }

    /**
     * @Route("/adminCategorie/create", name="admin.categorie.new")
     * @param Categorie $categorie
     */

    public function new (Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($categorie);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.categorie.index');
        }
        return $this->render('admin/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminCategorie/{id}", name="admin.categorie.edit", methods="GET|POST")
     * @param Categorie $categorie
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Categorie $categorie, Request $request)
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.categorie.index');
        }

        return $this->render('admin/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminCategorie/{id}", name="admin.categorie.delete", methods="DELETE")
     * @param Categorie $categorie
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Categorie $categorie, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->get('_token'))){
            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.categorie.index');
    }
}