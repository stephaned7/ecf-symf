<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\RoomRating;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeeRatingsController extends AbstractController
{
    #[Route('/see/ratings/{id}', name: 'app_see_ratings')]
    public function index(ManagerRegistry $doctrine, $id, Security $security): Response
    {

        if (!$security->isGranted('IS_NOT_BANNED')) {
            return $this->redirectToRoute('app_home');
        }

        $ratings = $doctrine->getRepository(RoomRating::class)->findAll();
        $ratingAuthor = $doctrine->getRepository(RoomRating::class)->getRatingAuthor($id);
        $salle = $doctrine->getRepository(Salle::class)->find($id);
        $users = $doctrine->getRepository(User::class)->findAll();
        $avgRating = $doctrine->getRepository(RoomRating::class)->getAverageRating($id);

        return $this->render('see_ratings/index.html.twig', [
            'ratings' => $ratings,
            'ratingAuthor' => $ratingAuthor,
            'users' => $users,
            'salle' => $salle,
            'avgRating' => $avgRating
        ]);
    }

    

    #[Route('/rating/delete/{id}', name: 'app_rating_delete')]
    public function delete(EntityManagerInterface $em, RoomRating $rating, $id, Security $security): Response
    {
        if(!$security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }

        $roomId = $rating->getRoom()->getId();
        $rating = $em->getRepository(RoomRating::class)->find($id);
        $em->remove($rating);
        $em->flush();

        return $this->redirectToRoute('app_see_ratings', ['id' => $roomId]);
    }
}
