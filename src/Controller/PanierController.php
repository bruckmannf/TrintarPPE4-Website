<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{

    /**
     * @Route ("/panier", name="panier")
     */
    public function index(MagasinRepository $magasinRepository, SessionInterface $session, ProduitRepository $produitRepository)
    {
        $panier = $session->get('panier', []);
        $livraison = 3.99;
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'livraison' => $livraison,
                'quantity' => $quantity
            ];
        }
        $total = 0;

        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrixht() * $item['quantity'] + $item['livraison'];
            $totalTest = 0;
            $total += $totalItem;
        }
        $magasins = $magasinRepository->findAll();

        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'livraison' => $livraison,
            'magasins' => $magasins,
             'total' => $total
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier.add")
     */
    public function add($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("trintar.produit");
    }

    /**
     * @Route("panier/remove/{id}", name="panier.remove")
     */
    public function remove($id, SessionInterface $session){
        $panier = $session->get('panier', []);
        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("panier/unAjout/{id}", name="panier.unAjout")
     */
    public function unAjout($id, SessionInterface $session, Produit $produit){

        $panier = $session->get('panier', []);
        $resultQuantite = $panier[$id];
        $resultStock = $produit->getStock();

        if ($resultQuantite <= $resultStock) {
            $panier[$id]++;
        } elseif ($resultQuantite > $resultStock) {
            $panier[$id];
            $this->addFlash('success', 'Erreur, votre quantité ne peut pas dépasser le stock maximal.');
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("panier/unAjout/{id}", name="panier.unAjout")
     */
    public function ajoutAdresse($id, SessionInterface $session, Produit $produit)
    {
    }

    /**
     * @Route("panier/uneSupression/{id}", name="panier.uneSuppresion")
     */
    public function uneSuppression($id, SessionInterface $session){
        $panier = $session->get('panier', []);
        $result = $panier[$id];
            if ($result > 1) {
                $panier[$id]--;
            } elseif ($result <= 1) {
                unset($panier[$id]);
            }
        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
    }
}
