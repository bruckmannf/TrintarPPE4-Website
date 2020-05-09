<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\infoCommande;
use App\Entity\Informer;
use App\Entity\Magasin;
use App\Entity\commander;
use App\Form\BancaireType;
use App\Repository\CommandeRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /** KernelInterface $appKernel */
    private $appKernel;

    /**
     * @var UtilisateurRepository
     */

    private $repository;

    public function __construct(EntityManagerInterface $em, CommandeRepository $Crepository, MagasinRepository $magasinRepository, \Swift_Mailer $mailer, KernelInterface $appKernel, UtilisateurRepository $repository)
    {
        $this->repository = $repository;
        $this->Crepository = $Crepository;
        $this->magasinRepository = $magasinRepository;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->appKernel = $appKernel;
    }

    /**
     * @Route("/{id}", name="commande")
     * @param Request $request
     */
    public function poursuite($id, SessionInterface $session, ProduitRepository $produitRepository, Request $request, MagasinRepository $magasinRepository){

        $panier = $session->get('panier', []);
        $adresse = $session->get('adresse', []);
        $livraison = 0;
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
            'magasins' => $magasins,
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
        $livraison = 3.99;
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
    public function paiement($id, SessionInterface $session, ProduitRepository $produitRepository, Request $request, MagasinRepository $magasinRepository)
    {

        $panier = $session->get('panier', []);
        $adresse = $session->get('adresse', []);
        setlocale(LC_TIME, 'fra_fra');
        $date = (strftime('%d/%m/%y'));
        $dateLivraison = (strftime('%d/%m/%y', strtotime('+1 week')));
        $livraison = 3.99;
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
        return $this->render('panier/paiement.html.twig', [
            'items' => $panierWithData,
            'livraison' => $livraison,
            'total' => $total,
            'item' => $item,
            'adresse' => $adresse,
            'date' => $date,
            'dateLivraison' => $dateLivraison,
        ]);
    }

    /**
     * @Route("/apresPaiement/{id}", name="apresPaiement")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param MailerService $mailerService
     * @param \Swift_Mailer $mailer
     */
    public function apresPaiement(AuthenticationUtils $authenticationUtils, $id, SessionInterface $session, ProduitRepository $produitRepository, Request $request, \Swift_Mailer $mailer, MailerService $mailerService, UserInterface $user)
    {
        $panier = $session->get('panier', []);
        $adresse = $session->get('adresse', []);
        $date = (date('Y-m-d'));
        $dateLivraison = (date('Y-m-d', strtotime('+ 7 days')));
        $livraison = 3.99;
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
        foreach ($panier as $id => $quantity){
            $test = $item['quantity'];
            $test1 = $produitRepository->find($id)->getStock();
            $test2 = $test1 - $test;
            $test3 = $produitRepository->find($id)->setStock($test2);
            $this->em->persist($test3);
            $this->em->flush();
        }
        $lastUsername = $authenticationUtils->getLastUsername();
        $token = $this->generateToken();
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('default/mypdf.html.twig', [
            'items' => $panierWithData,
            'item' => $item,
            'adresse' => $adresse,
            'token' => $token,
            'date' => $date,
            'dateLivraison' => $dateLivraison,
            'total' => $total,
            'livraison' => $livraison
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $publicDirectory = $this->appKernel->getProjectDir() . '/public/facture';
        $pdfFilepath =  $publicDirectory . '/Facture - '. $user->getNom().' '.$user->getPrenom().'.pdf';
        file_put_contents($pdfFilepath, $output);
        $data = \Swift_Attachment::fromPath($this->appKernel->getProjectDir() .'/public/facture/Facture - '. $user->getNom().' '.$user->getPrenom().'.pdf', 'application/html');

        $message = (new \Swift_Message('Votre facture n°'.$token))
            ->setFrom('test42@gmail.com')
            ->setTo($lastUsername)
            ->setBody(
                $this->renderView(
                    'emails/paiement.html.twig', [
                        'token' => $token,
                        'total' => $total,
                    ]
                ),
                'text/html'
            )
        ;
        $message->attach($data);
        $this->mailer->send($message);
        $panier = $session->set('panier', []);
        $adresse = $session->set('adresse', []);
        $commande = new Commande();
        $envoie = $commande->setPrixTotal($total);
        $envoie = $commande->setNumeroCommande($token);
        $envoie = $commande->setDateCde($date);
        $envoie = $commande->setDateLivraison($dateLivraison);
        $envoie = $commande->setFacturePdf('Facture - '.$user->getNom().' '.$user->getPrenom().'.pdf');
        $this->em->persist($envoie);
        $this->em->flush();
        foreach ($panierWithData as $item){
            $info = new infoCommande();
            $info1 = $item['quantity'];
            $info2 = $item['produit']->getPrixht();
            $info3 = $info->setQuantite($info1);
            $info3 = $info->setPrixUnitaire($info2);
            $this->em->persist($info3);
            $this->em->flush();
        }
        foreach ($panierWithData as $item){
            $relation = new commander();
            $relation1 = ($item['produit']->getId());
            $relation2 = $envoie->getId();
            $relation3 = $user->getId();
            $relation4 = $relation->setProduitId($relation1);
            $relation4 = $relation->setCommandeId($relation2);
            $relation4 = $relation->setUtilisateurId($relation3);
            $this->em->persist($relation4);
            $this->em->flush();
        }
        foreach ($panierWithData as $item){
            $informer = new Informer();
            $informer1 = ($item['produit']->getId());
            $informer2 = $envoie->getId();
            $informer3 = $info3->getId();
            $informer4 = $informer->setProduitId($informer1);
            $informer4 = $informer->setCommandeId($informer2);
            $informer4 = $informer->setInfoCommandeId($informer3);
            $this->em->persist($informer4);
            $this->em->flush();
        }
        return $this->render('panier/apresPaiement.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'item' => $item,
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(10)), '+/', '-_'), '=');
    }

}
