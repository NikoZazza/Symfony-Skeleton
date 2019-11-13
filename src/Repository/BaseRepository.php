<?php

namespace App\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository {
    /**
     * @param $dql
     * @param int $page
     * @param $limit
     * @return Paginator
     */
    public function paginate($dql, $page = 1, $limit) {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        return $paginator;
    }
}