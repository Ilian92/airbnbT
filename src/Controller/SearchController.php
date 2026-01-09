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
        $query = $request->query->getString('q');
        $query = trim($query) !== '' ? trim($query) : null;

        $authorIdRaw = $request->query->get('author');
        $authorId = is_numeric($authorIdRaw) ? (int) $authorIdRaw : null;

        $genreIdRaw = $request->query->get('genre');
        $genreId = is_numeric($genreIdRaw) ? (int) $genreIdRaw : null;

        $minPriceRaw = $request->query->get('min_price');
        $minPrice = is_numeric($minPriceRaw) ? (int) $minPriceRaw : null;

        $maxPriceRaw = $request->query->get('max_price');
        $maxPrice = is_numeric($maxPriceRaw) ? (int) $maxPriceRaw : null;

        $sort = $request->query->getString('sort', 'name');
        $direction = $request->query->getString('direction', 'asc');
        $page = max(1, $request->query->getInt('page', 1));
        $limit = $request->query->getInt('limit', 12);

        $pagination = $bookRepository->searchBooksPaginated(
            $query,
            $authorId,
            $genreId,
            $minPrice,
            $maxPrice,
            $sort,
            $direction,
            $page,
            $limit,
        );
        $authors = $authorRepository->findAll();
        $genres = $genreRepository->findAll();

        return $this->render('search/index.html.twig', [
            'books' => $pagination['items'],
            'pagination' => $pagination,
            'authors' => $authors,
            'genres' => $genres,
            'currentQuery' => $query,
            'currentAuthor' => $authorId,
            'currentGenre' => $genreId,
            'currentMinPrice' => $minPrice,
            'currentMaxPrice' => $maxPrice,
            'currentSort' => $sort,
            'currentDirection' => strtolower($direction),
        ]);
    }
}
