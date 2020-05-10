<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Licence;
use App\Form\LicenceType;
use App\Repository\LicenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminLicenceController extends AbstractController
{

    /**
     * @var LicenceRepository
     */

    private $Lrepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, LicenceRepository $Lrepository)
    {
        $this->Lrepository = $Lrepository;
        $this->em = $em;
    }

    /**
     * @route("/adminLicence", name = "admin.licence.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $lesClients=$this->getDoctrine()->getRepository(Licence::class)->findAll();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $reponse = new Response();
        $reponse2 = new Response();

        $reponse->setContent($serializer->serialize($lesClients, 'json'));
        $reponse->headers->set('Content-Type', 'application/json');

        $reponse2->setContent($serializer->serialize($lesClients, 'xml'));
        $reponse2->headers->set('Content-Type', 'application/xml');

        $fp = fopen('resultsLicence.json', 'w');
        fwrite($fp, $serializer->serialize($lesClients, 'json'));
        fclose($fp);

        $fp2 = fopen('resultsLicence.xml', 'w');
        fwrite($fp2, $serializer->serialize($lesClients, 'xml'));
        fclose($fp2);
        $licences = $this->Lrepository->findAll();
        return $this->render('admin/licence/index.html.twig', compact('licences'));
    }

    /**
     * @Route("/adminLicence/create", name="admin.licence.new")
     * @param Licence $licence
     */

    public function new (Request $request)
    {
        $licence = new Licence();
        $date = (date('Y-m-d'));
        $test = $licence->setCreatedAt($date);
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $test = $licence->setCreatedAt($date);
            $this->em->persist($licence);
            $this->em->persist($test);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.licence.index');
        }
        return $this->render('admin/licence/new.html.twig', [
            'licence' => $licence,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminLicence/{id}", name="admin.licence.edit", methods="GET|POST")
     * @param Licence $licence
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Licence $licence, Request $request)
    {
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.licence.index');
        }

        return $this->render('admin/licence/edit.html.twig', [
            'licence' => $licence,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminLicence/{id}", name="admin.licence.delete", methods="DELETE")
     * @param Licence $licence
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Licence $licence, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $licence->getId(), $request->get('_token'))){
            $this->em->remove($licence);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.licence.index');
    }
}