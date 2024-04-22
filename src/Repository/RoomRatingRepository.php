<?php

namespace App\Repository;

use App\Entity\RoomRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomRating>
 *
 * @method RoomRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomRating[]    findAll()
 * @method RoomRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomRating::class);
    }

    //    /**
    //     * @return RoomRating[] Returns an array of RoomRating objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RoomRating
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}