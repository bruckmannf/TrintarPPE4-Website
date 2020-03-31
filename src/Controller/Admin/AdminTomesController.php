<?php

namespace App\Controller\Admin;

use App\Entity\Tomes;
use App\Form\TomesType;
use App\Repository\TomesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTomesController extends AbstractController
{

    /**
     * @var TomesRepository
     */

    private $Trepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, TomesRepository $Trepository)
    {
        $this->Trepository = $Trepository;
        $this->em = $em;
    }

    /**
     * @route("/adminTomes", name = "admin.tomes.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $tomes = $this->Trepository->findAll();
        return $this->render('admin/tomes/index.html.twig', compact('tomes'));
    }

    /**
     * @Route("/adminTomes/create", name="admin.tomes.new")
     * @param Tomes $tomes
     */

    public function new (Request $request)
    {
        $tomes = new Tomes();
        $form = $this->createForm(TomesType::class, $tomes);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($tomes);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.tomes.index');
        }
        return $this->render('admin/tomes/new.html.twig', [
            'tomes' => $tomes,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminTomes/{id}", name="admin.tomes.edit", methods="GET|POST")
     * @param Tomes $tomes
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Tomes $tomes, Request $request)
    {
        $form = $this->createForm(TomesType::class, $tomes);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.tomes.index');
        }

        return $this->render('admin/tomes/edit.html.twig', [
            'tomes' => $tomes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminTomes/{id}", name="admin.tomes.delete", methods="DELETE")
     * @param Tomes $tomes
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Tomes $tomes, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $tomes->getId(), $request->get('_token'))){
            $this->em->remove($tomes);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.tomes.index');
    }
}