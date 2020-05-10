<?php


namespace App\Controller\Admin;


use App\Entity\Commande;
use App\Entity\commander;
use App\Entity\infoCommande;
use App\Entity\Licence;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Repository\CommandeRepository;
use App\Repository\InfoCommandeRepository;
use App\Repository\LicenceRepository;
use App\Repository\MagasinRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/adminStat", name = "admin.stat.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $lesClients=$this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        $lesProduitsdispo=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        $lesFactures=$this->getDoctrine()->getRepository(Commande::class)->findAll();
        $lesProduits=$this->getDoctrine()->getManager()->getRepository(infoCommande::class)->findAll();
        $lesMagasins=$this->getDoctrine()->getManager()->getRepository(Magasin::class)->findAll();
        $lesLicences=$this->getDoctrine()->getManager()->getRepository(Licence::class)->findAll();
        $totalInscrit = 0;
        $totalFacture = 0;
        $totalProduit = 0;
        $produit = 0;
        $magasin = 0;
        $licence = 0;
        foreach ($lesClients as $key){
            $totalInscrit++;
        }
        foreach ($lesFactures as $key){
            $totalFacture++;
        }
        foreach ($lesProduits as $items) {
            $test = $items->getQuantite();
            $totalProduit = $test + $totalProduit;
        }
        foreach ($lesProduitsdispo as $items2) {
            $test2 = $items2->getStock();
            $produit = $test2 + $produit;
        }
        foreach ($lesMagasins as $items3) {
            $magasin++;
        }
        foreach ($lesLicences as $items4) {
            $licence++;
        }
        return $this->render('admin/stat/index.html.twig', [
            'totalInscrit' => $totalInscrit,
            'totalFacture' => $totalFacture,
            'totalProduit' => $totalProduit,
            'produit' => $produit,
            'magasin' => $magasin,
            'licence' => $licence
        ]);
    }

    /**
     * @Route("/adminStat/mois", name = "admin.stat.mois")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mois(MagasinRepository $magasinRepository, LicenceRepository $licenceRepository, UtilisateurRepository $utilisateurRepository, CommandeRepository $commandeRepository, InfoCommandeRepository $infoCommandeRepository): Response
    {
        $now = new \DateTime();

        $lesClients=$utilisateurRepository->month($now);
        $lesProduitsdispo=$this->getDoctrine()->getRepository(Produit::class)->month($now);
        $lesFactures=$commandeRepository->month($now);
        $lesProduits=$infoCommandeRepository->month($now);
        $lesMagasins=$magasinRepository->month($now);
        $lesLicences=$licenceRepository->month($now);
        $totalInscrit = 0;
        $totalFacture = 0;
        $totalProduit = 0;
        $produit = 0;
        $magasin = 0;
        $licence = 0;
        foreach ($lesClients as $key){
            $totalInscrit++;
        }
        foreach ($lesFactures as $key){
            $totalFacture++;
        }
        foreach ($lesProduits as $items) {
            $test = $items->getQuantite();
            $totalProduit = $test + $totalProduit;
        }
        foreach ($lesProduitsdispo as $items2) {
            $test2 = $items2->getStock();
            $produit = $test2 + $produit;
        }
        foreach ($lesMagasins as $items3) {
            $magasin++;
        }
        foreach ($lesLicences as $items4) {
            $licence++;
        }
        return $this->render('admin/stat/mois.html.twig', [
            'totalInscrit' => $totalInscrit,
            'totalFacture' => $totalFacture,
            'totalProduit' => $totalProduit,
            'produit' => $produit,
            'magasin' => $magasin,
            'licence' => $licence
        ]);
    }

    /**
     * @Route("/adminStat/semaine", name = "admin.stat.semaine")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function semaine(MagasinRepository $magasinRepository, LicenceRepository $licenceRepository, UtilisateurRepository $utilisateurRepository, CommandeRepository $commandeRepository, InfoCommandeRepository $infoCommandeRepository): Response
    {
        $now = new \DateTime();

        $lesClients=$utilisateurRepository->week($now);
        $lesProduitsdispo=$this->getDoctrine()->getRepository(Produit::class)->week($now);
        $lesFactures=$commandeRepository->week($now);
        $lesProduits=$infoCommandeRepository->week($now);
        $lesMagasins=$magasinRepository->week($now);
        $lesLicences=$licenceRepository->week($now);
        $totalInscrit = 0;
        $totalFacture = 0;
        $totalProduit = 0;
        $produit = 0;
        $magasin = 0;
        $licence = 0;
        foreach ($lesClients as $key){
            $totalInscrit++;
        }
        foreach ($lesFactures as $key){
            $totalFacture++;
        }
        foreach ($lesProduits as $items) {
            $test = $items->getQuantite();
            $totalProduit = $test + $totalProduit;
        }
        foreach ($lesProduitsdispo as $items2) {
            $test2 = $items2->getStock();
            $produit = $test2 + $produit;
        }
        foreach ($lesMagasins as $items3) {
            $magasin++;
        }
        foreach ($lesLicences as $items4) {
            $licence++;
        }
        return $this->render('admin/stat/semaine.html.twig', [
            'totalInscrit' => $totalInscrit,
            'totalFacture' => $totalFacture,
            'totalProduit' => $totalProduit,
            'produit' => $produit,
            'magasin' => $magasin,
            'licence' => $licence
        ]);
    }

    /**
     * @Route("/adminStat/jour", name = "admin.stat.jour")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jour(MagasinRepository $magasinRepository, LicenceRepository $licenceRepository, UtilisateurRepository $utilisateurRepository, CommandeRepository $commandeRepository, InfoCommandeRepository $infoCommandeRepository): Response
    {
        $now = new \DateTime();

        $lesClients=$utilisateurRepository->day($now);
        $lesProduitsdispo=$this->getDoctrine()->getRepository(Produit::class)->day($now);
        $lesFactures=$commandeRepository->day($now);
        $lesProduits=$infoCommandeRepository->day($now);
        $lesMagasins=$magasinRepository->day($now);
        $lesLicences=$licenceRepository->day($now);
        $totalInscrit = 0;
        $totalFacture = 0;
        $totalProduit = 0;
        $produit = 0;
        $magasin = 0;
        $licence = 0;
        foreach ($lesClients as $key){
            $totalInscrit++;
        }
        foreach ($lesFactures as $key){
            $totalFacture++;
        }
        foreach ($lesProduits as $items) {
            $test = $items->getQuantite();
            $totalProduit = $test + $totalProduit;
        }
        foreach ($lesProduitsdispo as $items2) {
            $test2 = $items2->getStock();
            $produit = $test2 + $produit;
        }
        foreach ($lesMagasins as $items3) {
            $magasin++;
        }
        foreach ($lesLicences as $items4) {
            $licence++;
        }
        return $this->render('admin/stat/jour.html.twig', [
            'totalInscrit' => $totalInscrit,
            'totalFacture' => $totalFacture,
            'totalProduit' => $totalProduit,
            'produit' => $produit,
            'magasin' => $magasin,
            'licence' => $licence
        ]);
    }
}