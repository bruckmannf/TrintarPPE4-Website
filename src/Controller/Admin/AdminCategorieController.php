<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $lesClients=$this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $reponse = new Response();
        $reponse2 = new Response();

        $reponse->setContent($serializer->serialize($lesClients, 'json'));
        $reponse->headers->set('Content-Type', 'application/json');

        $reponse2->setContent($serializer->serialize($lesClients, 'xml'));
        $reponse2->headers->set('Content-Type', 'application/xml');

        $fp = fopen('resultsCategorie.json', 'w');
        fwrite($fp, $serializer->serialize($lesClients, 'json'));
        fclose($fp);

        $fp2 = fopen('resultsCategorie.xml', 'w');
        fwrite($fp2, $serializer->serialize($lesClients, 'xml'));
        fclose($fp2);
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
     * @Route("/adminCategorie/import", name="admin.categorie.import")
     * @param Request $request
     */
    public function import (Request $request) {
        $upload = new Categorie();
        $form = $this->createForm(CategorieType::class, $upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $upload->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $upload->setFile($fileName);

            return $this->redirectToRoute('admin.categorie.index');
        }
        return $this->render('admin/categorie/import.html.twig', [
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