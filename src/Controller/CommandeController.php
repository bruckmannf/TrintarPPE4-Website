<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Magasin;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @var MagasinRepository
     */

    private $magasinRepository;

    /**
     * @var CommandeRepository
     */

    private $Crepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, CommandeRepository $Crepository, MagasinRepository $magasinRepository)
    {
        $this->Crepository = $Crepository;
        $this->magasinRepository = $magasinRepository;
        $this->em = $em;
    }

    /**
     * @Route("/{id}", name="commande")
     * @param Request $request
     */
    public function poursuite($id, SessionInterface $session, ProduitRepository $produitRepository, Request $request, MagasinRepository $magasinRepository){

        $panier = $session->get('panier', []);
        $adresse = $session->get('adresse', []);
        $livraison = 4.99;
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'livraison' => $livraison,
                'quantity' => $quantity,
                'adresse' => $adresse,
            ];
        }
        $total = 0;
        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrixht() * $item['quantity'] + $item['livraison'];
            $total += $totalItem;
        }

        $magasins = $this->magasinRepository->findAll();
        $magasin = new Magasin();

        return $this->render('panier/poursuite.html.twig', [
            'items' => $panierWithData,
            'livraison' => $livraison,
            'magasins' => $magasins,
            'total' => $total,
            'item' => $item,
            'adresse' => $adresse,
            'magasin' => $magasin,
        ]);
    }

    /**
     * @Route("/commande/addLivraison/{id}/{nom}/{adresse}", name="commande.add.livraison")
     * @param $adresse
     * @param $nom
     * @return Response
     */
    public function addLivraison($id, $adresse, $nom, SessionInterface $session, MagasinRepository $magasinRepository, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$adresse])) {
            $panier[$adresse] = $adresse;
        }

        $session->set('adresse', $adresse);
        $magasins = $this->magasinRepository->findAll();
        $magasin = new Magasin();

        $livraison = 4.99;
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'livraison' => $livraison,
                'quantity' => $quantity,
            ];
        }
        $total = 0;

        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrixht() * $item['quantity'] + $item['livraison'];
            $total += $totalItem;
        }
        $magasins = $magasinRepository->findAll();

        return $this->render('panier/poursuite.html.twig',[
            'id' => $id,
            'magasins' => $magasins,
            'magasin' => $magasin,
            'adresse' => $adresse,
            'item' => $item
        ]);
    }

    /**
     * @Route("/paiement/{id}", name="paiement")
     * @param Request $request
     */
    public function paiement($id, SessionInterface $session, ProduitRepository $produitRepository, Request $request, MagasinRepository $magasinRepository){
        $panier = $session->get('panier', []);
        $adresse = $session->get('adresse', []);
        setlocale(LC_TIME, 'fra_fra');
        $date = (strftime('%d/%m/%y'));
        $dateLivraison = (strftime('%d/%m/%y', strtotime('+1 week')));
        $livraison = 4.99;
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'livraison' => $livraison,
                'quantity' => $quantity,
                'adresse' => $adresse,
                'date' => $date,
                'dateLivraison' => $dateLivraison,
            ];
        }
        $total = 0;
        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrixht() * $item['quantity'] + $item['livraison'];
            $total += $totalItem;
        }

        $magasins = $this->magasinRepository->findAll();
        $magasin = new Magasin();

        return $this->render('panier/paiement.html.twig', [
            'items' => $panierWithData,
            'livraison' => $livraison,
            'magasins' => $magasins,
            'total' => $total,
            'item' => $item,
            'adresse' => $adresse,
            'magasin' => $magasin,
            'date' => $date,
            'dateLivraison' => $dateLivraison
        ]);
    }

}
