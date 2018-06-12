<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 12.06.2018
 * Time: 13:58
 */
declare(strict_types=1);

namespace App\Repositories\Doctrine;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{


    /**
     * @param string|null $sort
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function allWithSortLimitOffset(string $sort = null, int $limit = null, int $offset = null)
    {
        $sort = trim((string)$sort);
        $criteria = Criteria::create();

        $qb = $this->createQueryBuilder('e');

        if ($sort && $this->checkSort($sort)) {
            $criteria = $criteria->orderBy([$sort => SORT_ASC]);
        }
        if ($limit > 0) {
            $criteria->setMaxResults($limit);
        }
        if ($offset >= 0) {
            $criteria->setFirstResult($offset);
        }
        $qb = $qb->addCriteria($criteria);

        return $qb->getQuery()->getResult();
    }

    private function checkSort(string $sort): bool
    {
        return $sort === 'userName' || $sort === 'email' || $sort === 'resolved';
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('e')
            ->select('count(e.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
