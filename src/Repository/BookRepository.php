<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function searchBooks(?string $query, ?int $authorId, ?int $genreId, ?int $minPrice, ?int $maxPrice): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->addSelect('a');

        if ($query) {
            $terms = preg_split('/\s+/', trim($query)) ?: [];
            $terms = array_values(array_filter($terms, static fn(string $t) => $t !== ''));

            foreach ($terms as $index => $term) {
                $paramName = 'term_' . $index;
                $qb->andWhere(
                    sprintf(
                        '(LOWER(b.name) LIKE :%1$s OR LOWER(a.name) LIKE :%1$s OR LOWER(a.lastName) LIKE :%1$s)',
                        $paramName
                    )
                )
                    ->setParameter($paramName, '%' . mb_strtolower($term) . '%');
            }
        }

        if ($authorId) {
            $qb->andWhere('a.id = :authorId')
                ->setParameter('authorId', $authorId);
        }

        if ($genreId) {
            $qb->leftJoin('b.genre', 'g')
                ->andWhere('g.id = :genreId')
                ->setParameter('genreId', $genreId);
        }

        if ($minPrice) {
            $qb->andWhere('b.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice) {
            $qb->andWhere('b.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array{items: list<Book>, total: int, page: int, pages: int, limit: int}
     */
    public function searchBooksPaginated(
        ?string $query,
        ?int $authorId,
        ?int $genreId,
        ?int $minPrice,
        ?int $maxPrice,
        string $sort,
        string $direction,
        int $page,
        int $limit,
    ): array {
        $page = max(1, $page);
        $limit = max(1, min(48, $limit));

        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $sortField = match ($sort) {
            'price' => 'b.price',
            'author' => 'a.lastName',
            default => 'b.name',
        };

        $qb = $this->createQueryBuilder('b')
            ->distinct()
            ->leftJoin('b.author', 'a')
            ->addSelect('a');

        if ($query) {
            $terms = preg_split('/\s+/', trim($query)) ?: [];
            $terms = array_values(array_filter($terms, static fn(string $t) => $t !== ''));

            foreach ($terms as $index => $term) {
                $paramName = 'term_' . $index;
                $qb->andWhere(
                    sprintf(
                        '(LOWER(b.name) LIKE :%1$s OR LOWER(a.name) LIKE :%1$s OR LOWER(a.lastName) LIKE :%1$s)',
                        $paramName
                    )
                )
                    ->setParameter($paramName, '%' . mb_strtolower($term) . '%');
            }
        }

        if ($authorId) {
            $qb->andWhere('a.id = :authorId')
                ->setParameter('authorId', $authorId);
        }

        if ($genreId) {
            $qb->leftJoin('b.genre', 'g')
                ->andWhere('g.id = :genreId')
                ->setParameter('genreId', $genreId);
        }

        if ($minPrice !== null) {
            $qb->andWhere('b.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('b.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        $qb->orderBy($sortField, $direction)
            ->addOrderBy('b.id', 'DESC');

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb->getQuery());
        $total = count($paginator);
        $pages = (int) max(1, (int) ceil($total / $limit));

        return [
            'items' => iterator_to_array($paginator->getIterator(), false),
            'total' => $total,
            'page' => $page,
            'pages' => $pages,
            'limit' => $limit,
        ];
    }
}
