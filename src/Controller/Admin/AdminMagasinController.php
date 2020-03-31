<?php

namespace App\Controller\Admin;

use App\Entity\Associer;
use App\Entity\Magasin;
use App\Form\MagasinType;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProduitType;

class AdminMagasinController extends AbstractController {

    /**
     * @var MagasinRepository
     */

    private $Mrepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, MagasinRepository $Mrepository)
    {
        $this->Mrepository = $Mrepository;
        $this->em = $em;
    }

    /**
     * @route("/adminMagasin", name = "admin.magasin.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $magasins = $this->Mrepository->findAll();
        return $this->render('admin/magasin/magasin.html.twig', compact('magasins'));
    }

    /**
     * @Route("/adminMagasin/create", name="admin.magasin.new")
     * @param Magasin $magasin
     */

    public function new (Request $request)
    {
        $magasin = new Magasin();
        $form = $this->createForm(MagasinType::class, $magasin);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($magasin);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.magasin.index');
        }
        return $this->render('admin/magasin/new.html.twig', [
            'magasin' => $magasin,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminMagasin/{id}", name="admin.magasin.edit", methods="GET|POST")
     * @param Magasin $magasin
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Magasin $magasin, Request $request)
    {
        $form = $this->createForm(MagasinType::class, $magasin);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.magasin.index');
        }

        return $this->render('admin/magasin/edit.html.twig', [
            'magasin' => $magasin,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminMagasin/{id}", name="admin.magasin.delete", methods="DELETE")
     * @param Magasin $magasin
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Magasin $magasin, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $magasin->getId(), $request->get('_token'))){
            $this->em->remove($magasin);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.magasin.index');
    }


}
