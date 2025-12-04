<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(
        Request $request,
        BookRepository $bookRepository,
        AuthorRepository $authorRepository,
        GenreRepository $genreRepository
    ): Response {
        $query = $request->query->get('q');
        $authorId = $request->query->get('author');
        $genreId = $request->query->get('genre');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');

        $books = $bookRepository->searchBooks($query, $authorId, $genreId, $minPrice, $maxPrice);
        $authors = $authorRepository->findAll();
        $genres = $genreRepository->findAll();

        return $this->render('search/index.html.twig', [
            'books' => $books,
            'authors' => $authors,
            'genres' => $genres,
            'currentQuery' => $query,
            'currentAuthor' => $authorId,
            'currentGenre' => $genreId,
            'currentMinPrice' => $minPrice,
            'currentMaxPrice' => $maxPrice,
        ]);
    }
}
