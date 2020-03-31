<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Role;
use App\Form\CategorieType;
use App\Form\RoleType;
use App\Repository\CategorieRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRoleController extends AbstractController
{

    /**
     * @var RoleRepository
     */

    private $Rrepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, RoleRepository $Rrepository)
    {
        $this->Rrepository = $Rrepository;
        $this->em = $em;
    }

    /**
     * @route("/adminRole", name = "admin.role.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $roles = $this->Rrepository->findAll();
        return $this->render('admin/role/index.html.twig', compact('roles'));
    }

    /**
     * @Route("/adminRole/create", name="admin.role.new")
     * @param Role $role
     */

    public function new (Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($role);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.role.index');
        }
        return $this->render('admin/role/new.html.twig', [
            'role' => $role,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminRole/{id}", name="admin.role.edit", methods="GET|POST")
     * @param Role $role
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Role $role, Request $request)
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.role.index');
        }

        return $this->render('admin/role/edit.html.twig', [
            'role' => $role,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminRole/{id}", name="admin.role.delete", methods="DELETE")
     * @param Role $role
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Role $role, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $role->getId(), $request->get('_token'))){
            $this->em->remove($role);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.role.index');
    }
}