<?php

namespace App\Controller\Admin;

use App\Entity\Masque;
use App\Form\MasqueType;
use App\Repository\MasqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminMasqueController extends AbstractController
{

    /**
     * @var MasqueRepository
     */

    private $MArepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, MasqueRepository $MArepository)
    {
        $this->MArepository = $MArepository;
        $this->em = $em;
    }

    /**
     * @route("/adminMasque", name = "admin.masque.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $lesClients=$this->getDoctrine()->getRepository(Masque::class)->findAll();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $reponse = new Response();
        $reponse2 = new Response();

        $reponse->setContent($serializer->serialize($lesClients, 'json'));
        $reponse->headers->set('Content-Type', 'application/json');

        $reponse2->setContent($serializer->serialize($lesClients, 'xml'));
        $reponse2->headers->set('Content-Type', 'application/xml');

        $fp = fopen('resultsOption.json', 'w');
        fwrite($fp, $serializer->serialize($lesClients, 'json'));
        fclose($fp);

        $fp2 = fopen('resultsOption.xml', 'w');
        fwrite($fp2, $serializer->serialize($lesClients, 'xml'));
        fclose($fp2);

        $masques = $this->MArepository->findAll();
        return $this->render('admin/masque/index.html.twig', compact('masques'));
    }

    /**
     * @Route("/adminMasque/create", name="admin.masque.new")
     * @param Masque $masque
     */

    public function new (Request $request)
    {
        $masque = new Masque();
        $form = $this->createForm(MasqueType::class, $masque);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($masque);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.masque.index');
        }
        return $this->render('admin/masque/new.html.twig', [
            'masque' => $masque,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminMasque/{id}", name="admin.masque.edit", methods="GET|POST")
     * @param Masque $masque
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Masque $masque, Request $request)
    {
        $form = $this->createForm(MasqueType::class, $masque);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.masque.index');
        }

        return $this->render('admin/masque/edit.html.twig', [
            'masque' => $masque,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminMasque/{id}", name="admin.masque.delete", methods="DELETE")
     * @param Masque $masque
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Masque $masque, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $masque->getId(), $request->get('_token'))){
            $this->em->remove($masque);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.masque.index');
    }
}