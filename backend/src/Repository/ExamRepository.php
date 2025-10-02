<?php

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exam>
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exam::class);
    }

       /**
        * @return Exam[] Returns an array of Exam objects
        */
       public function findByLocation($value): array
       {
           return $this->createQueryBuilder('e')
               ->andWhere('e.location = :val')
               ->setParameter('val', $value)
               ->orderBy('e.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }

       /**
        * @return Exam Returns Exam objects
        */
       public function findOneByStudent($value): Exam
       {
           return $this->createQueryBuilder('e')
               ->andWhere('e.student_id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }
}
