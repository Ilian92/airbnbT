<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * Returns an array of Property objects matching the search criteria.
     */
    public function search(Request $request): array
    {
        $location = $request->query->get('location');
        $checkIn = $request->query->get('checkin');
        $checkOut = $request->query->get('checkout');
        $guests = $request->query->get('guests');

        $qb = $this->createQueryBuilder('p');

        if ($location) {
            $qb->andWhere('p.city LIKE :location' or 'p.address LIKE :location')
                ->setParameter('location', '%' . $location . '%');
        }

        if ($guests) {
            $qb->andWhere('p.maxGuests >= :guests AND p.maxGuests < :guests + 2')
                ->setParameter('guests', $guests);
        }

        $qb->orderBy('p.note', 'DESC');
        $qb->addOrderBy('p.maxGuests', 'ASC');

        

        // if ($checkIn && $checkOut) {
        //     $qb->andWhere('p.id NOT IN (
        //         SELECT b.property FROM App\Entity\Booking b
        //         WHERE (b.startDate <= :checkOut AND b.endDate >= :checkIn)
        //     )')
        //        ->setParameter('checkIn', new \DateTime($checkIn))
        //        ->setParameter('checkOut', new \DateTime($checkOut));
        // }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Property[] Returns an array of Property objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Property
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
