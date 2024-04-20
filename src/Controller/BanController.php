<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BanController extends AbstractController
{
    #[Route('/ban', name: 'app_ban')]

    
    
    public function index(UserRepository $userRepo): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }
        $bannedUsers = $userRepo->findBannedUsers();

        return $this->render('ban/index.html.twig', [
            'controller_name' => 'BanController',
            'bannedUsers' => $bannedUsers,
        ]);
    }

    #[Route('/unban/{id}', name: 'app_unban', methods: ['GET'])]
    public function unbanUser(User $user, EntityManagerInterface $entityManager): Response
    {

        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }

        $user->setBanned(false);
        $entityManager->flush();
        return $this->redirectToRoute('app_ban');
    }
}
