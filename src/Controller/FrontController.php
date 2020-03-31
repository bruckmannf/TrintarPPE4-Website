<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    /**
     * @var Environnement
     */

    private $twig;

    public function __construct($twig){
        $this->twig = $twig;
    }

    /**
     * @Route ("/", name="front.index")
     * @param ProduitRepository $Prepository
     * @return Response
     */
    public function index(ProduitRepository $Prepository): Response
    {
        $produits = $Prepository->findLatest();
        return $this->render('front/index.html.twig', [
            'produits' => $produits,
            'current_menu' => 'accueil'
        ]);
    }
}
