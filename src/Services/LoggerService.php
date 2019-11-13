<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

/**
 * @package App\Services
 */
class LoggerService {
    /** @var LoggerInterface */
    protected $logger;

    /** @var $logDir */
    protected $logDir;

    /**
     * LoggerService constructor.
     * @param LoggerInterface $logger
     * @param $logDir
     */
    public function __construct(LoggerInterface $logger, $logDir) {
        $this->logger = $logger;
        $this->logDir = $logDir;
    }

    /**
     * @return string
     */
    protected function getCallerMethod() {
        return '[' . debug_backtrace()[2]['class'] . '\\' . debug_backtrace()[2]['function'] . ']';
    }

    /**
     * @param $data
     */
    public function logDataRequest($data) {
        $this->logger->debug($this->getCallerMethod() . ' ' . json_encode($data));
    }

    /**
     * @param $message
     */
    public function logInfo($message) {
        $this->logger->info($this->getCallerMethod() . ' ' . $message);
    }

    /**
     * @param $message
     */
    public function logDebug($message) {
        $this->logger->debug($this->getCallerMethod() . ' ' . $message);
    }

    /**
     * @param $message
     */
    public function logWarning($message) {
        $this->logger->warning($this->getCallerMethod() . ' ' . $message);
    }

    /**
     * @param $message
     */
    public function logError($message) {
        $this->logger->error($this->getCallerMethod() . ' ' . $message);
    }

    /**
     * @param \Exception $exception
     */
    public function logException(\Exception $exception) {
        $this->logger->error("LINE ERROR: " . $exception->getLine());
        $this->logger->error("MESSAGE: " . $exception->getMessage());
        $this->logger->error("TRACE: " . $exception->getTraceAsString());
    }

    /**
     * @return mixed
     */
    public function getLogDir() {
        return $this->logDir;
    }
}