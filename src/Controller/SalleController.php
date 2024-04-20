<?php

namespace App\Controller;

use App\Repository\SalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SalleController extends AbstractController
{
    #[Route('/salle', name: 'app_salle')]
    
    public function index(SalleRepository $salleRepository): Response

    
    {
        $salles = $salleRepository->findSallesWithEquipements();
        
        return $this->render('salle/index.html.twig', [
            'salles' => $salles,
        ]);
    }
}
