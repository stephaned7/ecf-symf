<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Subscription;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Security\BannedUserVoter;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, ManagerRegistry $doctrine, BannedUserVoter $banCheck, Security $security): Response
    {
        
        $currentUser = $security->getUser();
        if(!$security->isGranted('ROLE_ADMIN') && $currentUser->getId() !== $user->getId()){
            return $this->redirectToRoute('app_user_show', ['id' => $currentUser->getId()]);
        }

        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }

        $plan = $doctrine->getRepository(Plan::class)->findAll();
        $sub = $doctrine->getRepository(Subscription::class)->findAll();
        

        
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'plans' => $plan,
            'subs' => $sub,
        ]);
    }

    #[Route('edit/{id}/', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()->getId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $this->getUser()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/ban', name: 'app_user_ban', methods: ['GET'])]
    public function ban(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setBanned(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}
