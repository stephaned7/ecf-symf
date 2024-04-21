<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/change-locale/{locale}', name: 'change_locale')]
    public function changeLocale($locale, Request $request): Response
    {
        //On stocke la langue demandé dans la session
        $request->getSession()->set('_locale', $locale);

        //On reviens sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
