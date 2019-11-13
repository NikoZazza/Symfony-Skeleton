<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;

/**
 * @package App\Services
 */
class HashService {
    /** @var EntityManager */
    protected $em;

    /** @var LoggerService */
    protected $log;

    /** @var string */
    protected $characters;

    /** @var string */
    protected $alphabetic;

    /**
     * HashService constructor.
     * @param EntityManager $entityManager
     * @param LoggerService $loggerService
     */
    public function __construct(EntityManager $entityManager, LoggerService $loggerService) {
        $this->em = $entityManager;
        $this->log = $loggerService;
        $this->characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->alphabetic = 'abcdefghijklmnopqrstuvwxyz';
    }

    /**
     * @param $counter
     * @param null $stringParam
     * @return string
     */
    public function createRandomString($counter, $stringParam = null) {
        $charactersLength = strlen($this->characters);
        $randomString = '';
        if ($stringParam != null)
            $randomString .= $stringParam;
        for ($i = 0; $i < $counter; $i++)
            $randomString .= $this->characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

    /**
     * @param $counter
     * @param null $stringParam
     * @return int|string
     */
    public function createRandomNumber($counter, $stringParam = null) {
        $randomString = '';
        if ($stringParam != null)
            $randomString = $stringParam;
        for ($i = 0; $i < $counter; $i++)
            $randomString .= mt_rand(0, 9);

        return $randomString;
    }
}