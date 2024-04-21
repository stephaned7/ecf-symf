<?php

namespace App\Controller;

use Stripe;
use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/stripe/{id}', name: 'app_stripe')]
    public function index(ManagerRegistry $doctrine, $id, Security $security, Request $request): Response
    {

        $user = $this->getUser();
        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $subbed = $user->getSubscription();
        $subUrl = $request->attributes->get('id');

        if($user->getSubscription()->getPlan()->getId() == $subUrl){
            $this->addFlash(
                'danger',
                'Vous êtes déjà abonné à ce plan!'
            );
            return $this->redirectToRoute('app_sub');
        }

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
    public function createCharge(Request $request, ManagerRegistry $doctrine, $id, Security $security)
    {
        $user = $this->getUser();
        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $plan = $doctrine->getRepository(Plan::class)->find($id);

        if (!$plan) {
            throw $this->createNotFoundException('No plan found for id ' . $id);
        }

        $existingSub = $doctrine->getRepository(Subscription::class)->findOneBy([
            'user' => $user,
            'plan' => $plan,
            'is_active' => true,
        ]);

        if ($existingSub) {
            $this->addFlash(
                'danger',
                'Vous êtes déjà abonné à ce plan!'
            );

            return $this->redirectToRoute('app_stripe', 
            ['id' => $plan->getId()],
            Response::HTTP_SEE_OTHER);
        }

        $activeSub = $doctrine->getRepository(Subscription::class)->findBy([
            'user' => $user,
            'is_active' => true,
        ]);

        $em = $doctrine->getManager();

        foreach($activeSub as $active){
            $active->setActive(false);
            $em->persist($active);
        }

        $em->flush();



        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create([
            "amount" => $plan->getPrice() * 100,
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => "Payment for plan " . $plan->getName()
        ]);

        

        $this->addFlash(
            'success',
            'Payment réussi, merci de votre abonnement!'
        );

        $user = $this->getUser();
        $sub = new Subscription();
        $sub->setUser($user);
        $sub->setPlan($plan);
        $sub->setStartingDate(new \DateTime());
        $sub->setEndingDate((new \DateTime())->modify('+1 month'));
        $sub->setActive(true);
        $user->setSubscription($sub);

        $em = $doctrine->getManager();
        $em->persist($sub);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_stripe', 
        ['id' => $plan->getId()],
        Response::HTTP_SEE_OTHER);
    }
}