<?php

namespace App\Repository;

use App\Entity\Trek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function PHPUnit\Framework\isEmpty;

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
        ?bool $isEnabled = null,
        $idLevels = null,
        $price = null
    )
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->distinct('t')
            ->andWhere('t.id IS NOT NULL')
            ->orderBy('t.price', 'ASC')
        ;

        if(is_bool($isEnabled)) {
            $qb
                ->leftJoin('t.status', 'st')
                ->andWhere('st.isEnabled = :isEnabled')
                ->setParameter('isEnabled', $isEnabled)
            ;
        }

        if($idLevels) {
            $qb
                ->leftJoin('t.level', 'lvl')
                ->andWhere('lvl.id IN (:idLevels)')
                ->setParameter('idLevels', $idLevels)
            ;
        }

        if($price) {
            $qb
                ->andWhere('t.price <= :price')
                ->setParameter('price', $price)
            ;
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
