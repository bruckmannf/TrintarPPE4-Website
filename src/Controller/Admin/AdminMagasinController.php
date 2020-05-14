<?php

namespace App\Controller\Admin;

use App\Entity\Associer;
use App\Entity\Magasin;
use App\Entity\optionMagasin;
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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $lesClients=$this->getDoctrine()->getRepository(Magasin::class)->findAll();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $reponse = new Response();
        $reponse2 = new Response();

        $reponse->setContent($serializer->serialize($lesClients, 'json', [
            'circular_reference_handler' => function ($magasin) {
                return $magasin->getId();
            }
        ]));
        $reponse->headers->set('Content-Type', 'application/json');

        $reponse2->setContent($serializer->serialize($lesClients, 'xml', [
            'circular_reference_handler' => function ($magasin) {
                return $magasin->getId();
            }
        ]));
        $reponse2->headers->set('Content-Type', 'application/xml');

        $fp = fopen('resultsMagasin.json', 'w');
        fwrite($fp, $serializer->serialize($lesClients, 'json', [
            'circular_reference_handler' => function ($magasin) {
                return $magasin->getId();
            }
        ]));
        fclose($fp);

        $fp2 = fopen('resultsMagasin.xml', 'w');
        fwrite($fp2, $serializer->serialize($lesClients, 'xml', [
            'circular_reference_handler' => function ($magasin) {
                return $magasin->getId();
            }
        ]));
        fclose($fp2);

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
        $date = (date('Y-m-d'));
        $test = $magasin->setCreatedAt($date);
        $form = $this->createForm(MagasinType::class, $magasin);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $test = $magasin->setCreatedAt($date);
            $this->em->persist($magasin);
            $this->em->persist($test);
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
