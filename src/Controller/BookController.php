<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/books', name: 'app_books')]
    public function index(BookRepository $books, CategoryRepository $categories) : Response {
        $livres = $books->findAll();
        $cat = $categories ->findAll();
        return $this->render('book/index.html.twig', [
            'livres'=>$livres,
            'cat' =>$cat
        ]);
    }

    #[Route('/{slug}', name: 'book_category')]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        $cat = $categoryRepository->findAll();

        if (!$categories) { 
            // throw new NotFoundHttpException("La catégorie demandée n'existe pas");
            // Deuxième méthode venant de l'AbstractController :
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('book/category.html.twig', [
            'slug' => $slug,
            'category' => $categories,
            'cat'=>$cat
        ]);
    }
    
    #[Route('/{category_slug}/{slug}', name: 'book_show')]
    public function show($slug, BookRepository $bookRepository, CategoryRepository $categorie)
    {
        $book = $bookRepository->findOneBy([
            'slug' => $slug
        ]);

        $cat = $categorie->findAll();

        if (!$book) {
            throw $this->createNotFoundException("Le livre demandé n'existe pas");
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'cat' => $cat
        ]);
    }
}
