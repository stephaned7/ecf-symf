<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Entity\Plan;
use App\Entity\User;
use Stripe\StripeClient;
use App\Entity\Subscription;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebhookController extends AbstractController
{
    #[Route('/webhook', name: 'app_webhook')]
    public function index(ManagerRegistry $doctrine, LoggerInterface $logger): Response
    {

        $logger->info('1Stripe');
        
        Stripe::setApiKey($this->getParameter('stripe_sk'));

        $endpoint_secret = $this->getParameter('stripe_webhook_secret');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try{
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e){
            $logger->info('Webhook Stripe invalid payload');
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e){
            $logger->info('Webhook Stripe invalid signature');
            http_response_code(403);
            exit();
        }

        switch($event->type){
            case 'checkout.session.completed':
                $logger->info('2Stripe');
                
                $logger->info('Webhook Stripe connect checkout.session.completed');
                $session = $event->data->object;
                $subscriptionId = $session->subscription;


                $logger->info('Event type: ' . $event->type);
                $logger->info('Subscription ID: ' . $subscriptionId);

                $stripe = new StripeClient($this->getParameter('stripe_sk'));
                $subscriptionStripe = $stripe->subscriptions->retrieve($subscriptionId, []);
                $planId = $subscriptionStripe->plan->id;

                $logger->info('3Stripe');

                $logger->info('Plan ID: ' . $planId);

                $customerEmail = $session->customer_details->email;

                $logger->info('Customer email: ' . $customerEmail);

                $user = $doctrine->getRepository(User::class)->findOneByEmail($customerEmail);
                if(!$user){
                    $logger->info('Webhook Stripe user not found');
                    http_response_code(404);
                    exit();
                }

                $logger->info('4Stripe');

                $em = $doctrine->getManager();
                $query = $em->createQuery(
                    'SELECT subscription, plan 
                    FROM App\Entity\Subscription subscription 
                    LEFT JOIN subscription.plan plan 
                    WHERE subscription.startingDate < :now 
                    AND subscription.endingDate > :now 
                    AND subscription.user = :user 
                    AND subscription.is_active = :true 
                    ORDER BY subscription.endingDate DESC'
                )
                ->setParameters([
                    'now' => new \DateTime(),
                    'user' => $user,
                    'true' => true,
                ]);
                
                $activeSubs = $query->getResult();
                
                foreach ($activeSubs as $activeSub) {
                    \Stripe\Subscription::update(
                        $activeSub->getStripeId(), [
                            'cancel_at_period_end' => false
                        ]
                    );
                
                    $activeSub->setActive(false);
                    $activeSub->setCanceled(true);
                    $em->persist($activeSub);
                }
                
                $logger->info('5Stripe');
                
                $plan = $doctrine->getRepository(Plan::class)->findOneBy(['stripe_id' => $planId]);
                if(!$plan){
                    $logger->info('Webhook Stripe plan not found');
                    http_response_code(404);
                    exit();
                }
                
                $logger->info('6Stripe');
                
                $subscription = new Subscription();
                $subscription->setPlan($plan);
                $subscription->setStripeId($subscriptionStripe->id);
                $subscription->setStartingDate(new \DateTime(date('c', $subscriptionStripe->current_period_start)));
                $subscription->setEndingDate(new \DateTime(date('c', $subscriptionStripe->current_period_end)));
                $subscription->setActive(true);
                $subscription->setUser($user);
                $user->setStripeId($session->customer);
                
                $logger->info('7Stripe');
                
                $em->persist($subscription);
                $em->flush();
                break;

            }

            $response = new Response('Webhook Stripe received', 200);
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
    }
}
