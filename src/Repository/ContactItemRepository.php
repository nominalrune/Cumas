<?php

namespace App\Repository;

use App\Entity\ContactItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactItem>
 *
 * @method ContactItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactItem[]    findAll()
 * @method ContactItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactItem::class);
    }

//    /**
//     * @return ContactItems[] Returns an array of ContactItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContactItem
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
