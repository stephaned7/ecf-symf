<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_sub')]
    public function index(ManagerRegistry $doctrine, Security $security): Response
    {
        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }

        $plan = $doctrine->getRepository(Plan::class)->findAll();
        $user = $doctrine->getRepository(User::class)->findAll();
        $sub = $doctrine->getRepository(Subscription::class)->findAll();


        return $this->render('subscription/index.html.twig', [
            'plans' => $plan,
            'users' => $user,
            'subs' => $sub,
        ]);
    }
}