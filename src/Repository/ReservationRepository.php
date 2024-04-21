<?php

namespace App\Repository;

use App\Entity\Salle;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findFutureAppointements():array
    {
        return $this->createQueryBuilder('r')
            ->where('r.start > :currentDate')
            ->setParameter('currentDate', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findByRoomId(int $salleId): array
    {
        return $this->createQueryBuilder('r')
                ->andWhere('r.salle = :salleId')
                ->setParameter('salleId', $salleId)
                ->getQuery()
                ->getResult();
    }

   public function findByConflictingReservations(Salle $salle, \DateTime $start, \DateTime $end)
   {
        return $this->createQueryBuilder('r')
                ->where('r.start = :salle')
                ->andWhere('r.start < :end')
                ->andWhere('r.end > :start')
                ->setParameter('salle', $salle)
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getQuery()
                ->getResult();
   }

   public function findReservationsByUserId($userId):array
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
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

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
