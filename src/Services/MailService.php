<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;

/**
 * @package App\Services
 */
class MailService {
    /** @var EntityManager */
    protected $em;

    /** @var LoggerService */
    protected $log;

    /** @var \Swift_Mailer */
    protected $mailer;

    /**
     * @param EntityManager $entityManager
     * @param LoggerService $loggerService
     * @param \Swift_Mailer $mailer
     */
    public function __construct(EntityManager $entityManager, LoggerService $loggerService, \Swift_Mailer $mailer) {
        $this->em = $entityManager;
        $this->log = $loggerService;
        $this->mailer = $mailer;
    }

    public function sendMail($email, $view, $title) {
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject('Skeleton - ' . $title)
                ->setFrom('example@example.com')
                ->setTo($email)
                ->setBody($view, 'text/html')
                ->setReplyTo(['help@example.com'])
                ->setContentType('text/html');
            $this->mailer->send($message);
            return true;
        } catch (\Exception $e) {
            $this->log->logError('Mail Service Error');
            $this->log->logError($e->getMessage());
        }
        return false;
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function dbSave($entity) {
        $this->em->persist($entity);
        $this->em->flush();
    }
}