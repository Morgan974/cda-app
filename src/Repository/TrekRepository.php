<?php

namespace App\Repository;

use App\Entity\Trek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trek|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trek|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trek[]    findAll()
 * @method Trek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trek::class);
    }

    /**
     * @param bool $isEnabled
     */
    public function listTrek(
        ?bool $isEnabled = null
    )
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->distinct('t')
            ->andWhere('t.id IS NOT NULL')
            ->orderBy('t.name', 'ASC')
        ;

        if(is_bool($isEnabled)) {
            $qb
                ->leftJoin('t.status', 'st')
                ->andWhere('st.isEnabled = :isEnabled')
                ->setParameter('isEnabled', $isEnabled)
            ;
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
