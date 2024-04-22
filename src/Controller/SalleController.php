<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\RoomRating;
use App\Repository\SalleRepository;
use App\Repository\RoomRatingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{
    #[Route('/salle', name: 'app_salle')]
    public function index(ManagerRegistry $doctrine, Security $security): Response
    {
        $salles = $doctrine->getRepository(Salle::class)->findSallesWithEquipements();

        if (!$security->isGranted('IS_NOT_BANNED')) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('salle/index.html.twig', [
            'salles' => $salles,
        ]);
    }
}
