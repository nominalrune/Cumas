<?php

namespace App\Repository;

use App\Entity\MailAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MailAccount>
 *
 * @method MailAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailAccount[]    findAll()
 * @method MailAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailAccount::class);
    }

//    /**
//     * @return MailAccount[] Returns an array of MailAccount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MailAccount
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
