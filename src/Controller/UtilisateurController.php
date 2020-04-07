<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UtilisateurController extends AbstractController {

    /**
     * @var EntityManagerInterface
     */

    private $em;

    /**
     * @var UtilisateurRepository
     */

    private $repository;

    public function __construct(UtilisateurRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route ("/utilisateur/profil", name="user.profil", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */

    public function profil(Request $request): Response
    {
        return $this->render('trintar/profil.html.twig', [
            'current_menu' => 'users',
        ]);
    }

    /**
     * @Route("/utilisateur/{id}", name="user.editProfil", methods="GET|POST")
     * @param Utilisateur $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editProfil(Utilisateur $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('user.profil');

        }

        return $this->render('trintar/editProfil.html.twig', [
            'user' => $user,
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("utilisateur/{id}", name="user.deleteProfil", methods="DELETE")
     * @param Utilisateur $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function deleteProfil(Utilisateur $user, Request $request,TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $this->em->remove($user);
            $tokenStorage->setToken(null);
            $session->invalidate();
            $this->em->flush();
        }
        return $this->redirectToRoute('app_logout');
    }
}




