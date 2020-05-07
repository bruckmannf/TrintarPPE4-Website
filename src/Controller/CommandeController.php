<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\CommandeRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use App\Security\LoginAuthenticator;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Stripe\Stripe;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\NotBlank;

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

    public function __construct(EntityManagerInterface $em, CommandeRepository $Crepository, MagasinRepository $magasinRepository, \Swift_Mailer $mailer, KernelInterface $appKernel)
    {
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
    public function apresPaiement(AuthenticationUtils $authenticationUtils, $id, SessionInterface $session, ProduitRepository $produitRepository, Request $request, MagasinRepository $magasinRepository, \Swift_Mailer $mailer, MailerService $mailerService)
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

        foreach ($panier as $id => $quantity){
            $test = $item['quantity'];
            $test1 = $produitRepository->find($id)->getStock();
            $test2 = $test1 - $test;
            $test3 = $produitRepository->find($id)->setStock($test2);
            $this->em->persist($test3);
            $this->em->flush();
        }

        $magasins = $this->magasinRepository->findAll();
        $magasin = new Magasin();

        $lastUsername = $authenticationUtils->getLastUsername();
        $token = $this->generateToken();

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
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
        $pdfFilepath =  $publicDirectory . '/Trintar - Facture.pdf';

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);
        $data = \Swift_Attachment::fromPath($this->appKernel->getProjectDir() .'/public/facture/Trintar - Facture.pdf', 'application/html');

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

        return $this->render('panier/apresPaiement.html.twig', [
            'items' => $panierWithData,
            'livraison' => $livraison,
            'magasins' => $magasins,
            'total' => $total,
            'item' => $item,
            'adresse' => $adresse,
            'magasin' => $magasin,
            'date' => $date,
            'dateLivraison' => $dateLivraison,
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
