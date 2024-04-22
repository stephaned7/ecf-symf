<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\RoomRating;
use App\Form\RoomRatingType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    #[Route('/rating/{id}', name: 'app_rating')]
    public function index(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $em, Security $security, $id): Response
    {
        $rating = new RoomRating();

        $form = $this->createForm(RoomRatingType::class, $rating, [
            'client' => $this->getUser(),
            'room' => $doctrine->getRepository(Salle::class)->find($id),
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $clientId = $form->get('client')->getData();
            $roomId = $form->get('room')->getData();
            $em->persist($rating);
            $em->flush();
        }
    
        return $this->render('rating/index.html.twig', [
            'form' => $form
        ]);
    }
}
