<?php

namespace App\Controller;


use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function homepage(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBy([], [], 6);
        return $this->render('home/index.html.twig', [
            'books' => $books,
        ]);
    }
}
