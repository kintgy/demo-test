<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function findAllByConditions(array $conditions)
    {
        return $this->createQueryBuilderByConditions($conditions)
            ->getQuery()
            ->getResult();
    }

    private function createQueryBuilderByConditions($conditions)
    {
        $queryBuilder = $this->createQueryBuilder('l');
        if (isset($conditions['createdTime'])) {
            $queryBuilder->andWhere('l.createdTime >= :createdTime')
                ->setParameter('createdTime', $conditions['createdTime']);
        }

        return $queryBuilder;
    }
}
