<?php

namespace App\Controller;

use Stripe;
use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/stripe/{id}', name: 'app_stripe')]
    public function index(ManagerRegistry $doctrine, $id): Response
    {

        // $user = $this->$user->findAll();
        // $sub = $this->$sub->findAll();

        $user = $doctrine->getRepository(User::class)->findBannedUsers();
        $plan = $doctrine->getRepository(Plan::class)->find($id);
        $sub = $doctrine->getRepository(Subscription::class)->findAll();


        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'user' => $user,
            'plan' => $plan,
            'subs' => $sub
        ]);
    }


    #[Route('/stripe/create-charge/{id}', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, ManagerRegistry $doctrine, $id)
    {
        // dump($id);
        $plan = $doctrine->getRepository(Plan::class)->find($id);

        if (!$plan) {
            throw $this->createNotFoundException('No plan found for id ' . $id);
        }

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create([
            "amount" => $plan->getPrice() * 100,
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => "Payment for plan " . $plan->getName()
        ]);

        

        $this->addFlash(
            'success',
            'Payment Successful!'
        );

        $user = $this->getUser();
        $sub = new Subscription();
        $sub->setUser($user);
        $sub->setPlan($plan);
        $sub->setStartingDate(new \DateTime());
        $sub->setEndingDate((new \DateTime())->modify('+1 month'));
        $sub->setActive(true);

        $em = $doctrine->getManager();
        $em->persist($sub);
        $em->flush();

        return $this->redirectToRoute('app_stripe', 
        ['id' => $plan->getId()],
        Response::HTTP_SEE_OTHER);
    }
}