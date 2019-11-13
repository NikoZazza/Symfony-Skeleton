<?php

namespace App\Repository;

class UserRepository extends BaseRepository {

    public function getCountUsersSQL() {
        $sql = "SELECT COUNT(*) as count_users FROM user";

        $em = $this->getEntityManager();
        $query = $em->getConnection()->prepare($sql);
        $query->execute();
        $res = $query->fetchAll();
        if (empty($res) || count($res) <= 0)
            return 0;
        return $res[0]["count_users"];
    }

    public function getCountUsers() {
        $qb = $this->createQueryBuilder('user');
        $qb->select('user', 'COUNT(*) as count_users');
        $res = $qb->getQuery()->getResult();
        if (empty($res) || count($res) <= 0)
            return 0;
        return $res[0]["count_users"];
    }
}
