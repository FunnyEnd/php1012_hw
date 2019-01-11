<?php

namespace Framework\Exception;

use Exception;
use Zaine\Log;

class ViewExtension extends Exception
{
    private $logger;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->logger = new Log("Framework\\View");
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}. {$this->file} at line {$this->line}\n{$this->getTraceAsString()}\n";
    }

    public function log()
    {
        $this->logger->error($this->__toString());
    }
}