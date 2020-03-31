<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\ProduitSearch;
use App\Form\ProduitSearchType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


class ProduitController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */

    private $em;


    /**
     * @var ProduitRepository
     */

    private $Prepository;

    public function __construct(ProduitRepository $Prepository, EntityManagerInterface $em)
    {
        $this->Prepository = $Prepository;
        $this->em = $em;
    }

    /**
     * @Route("/produits", name="trintar.produit", methods={"GET"})
     * @param ProduitRepository $Prepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator,ProduitRepository $Prepository, Request $request): Response
    {
        $search = new ProduitSearch();
        $form = $this->createForm(ProduitSearchType::class, $search);
        $form->handleRequest($request);

        $produits = $paginator->paginate(
            $this->Prepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('trintar/produit.html.twig', [
            'current_menu' => 'produits',
            'produits' => $produits,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/biens/{slug}-{id}", name="produit.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     * @param Produit $produit
     */

    public function show(Produit $produit, string $slug): Response
    {
        if ($produit->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $produit->getId(),
                'slug' => $produit->getSlug()
            ], 301);
        }
        return $this->render('trintar/showProduit.html.twig', [
            'produit' => $produit,
            'current_menu' => 'produits'
        ]);

    }
}
