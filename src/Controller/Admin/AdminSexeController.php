<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Sexe;
use App\Form\CategorieType;
use App\Form\SexeType;
use App\Repository\CategorieRepository;
use App\Repository\SexeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminSexeController extends AbstractController
{

    /**
     * @var SexeRepository
     */

    private $Srepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, SexeRepository $Srepository)
    {
        $this->Srepository = $Srepository;
        $this->em = $em;
    }

    /**
     * @route("/adminSexe", name = "admin.sexe.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $lesClients=$this->getDoctrine()->getRepository(Sexe::class)->findAll();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $reponse = new Response();
        $reponse2 = new Response();

        $reponse->setContent($serializer->serialize($lesClients, 'json'));
        $reponse->headers->set('Content-Type', 'application/json');

        $reponse2->setContent($serializer->serialize($lesClients, 'xml'));
        $reponse2->headers->set('Content-Type', 'application/xml');

        $fp = fopen('resultsSexe.json', 'w');
        fwrite($fp, $serializer->serialize($lesClients, 'json'));
        fclose($fp);

        $fp2 = fopen('resultsSexe.xml', 'w');
        fwrite($fp2, $serializer->serialize($lesClients, 'xml'));
        fclose($fp2);
        $sexes = $this->Srepository->findAll();
        return $this->render('admin/sexe/index.html.twig', compact('sexes'));
    }

    /**
     * @Route("/adminSexe/create", name="admin.sexe.new")
     * @param Sexe $sexe
     */

    public function new (Request $request)
    {
        $sexe = new Sexe();
        $form = $this->createForm(SexeType::class, $sexe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($sexe);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.sexe.index');
        }
        return $this->render('admin/sexe/new.html.twig', [
            'sexe' => $sexe,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminSexe/{id}", name="admin.sexe.edit", methods="GET|POST")
     * @param Sexe $sexe
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Sexe $sexe, Request $request)
    {
        $form = $this->createForm(SexeType::class, $sexe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.sexe.index');
        }

        return $this->render('admin/sexe/edit.html.twig', [
            'sexe' => $sexe,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminSexe/{id}", name="admin.sexe.delete", methods="DELETE")
     * @param Sexe $sexe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Sexe $sexe, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $sexe->getId(), $request->get('_token'))){
            $this->em->remove($sexe);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.sexe.index');
    }
}