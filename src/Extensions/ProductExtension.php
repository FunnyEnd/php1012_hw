<?php

namespace App\Extensions;

use Exception;

class ProductExtension extends Exception
{
  public function __construct($message, $code = 0, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }

  public function __toString()
  {
    return __CLASS__ . ": [{$this->code}]: {$this->message}. {$this->file} at line {$this->line}\n{$this->getTraceAsString()}\n";
  }

  public function log()
  {
    $logMess = $this->__toString();
    file_put_contents('error-log.txt', $logMess, FILE_APPEND);
  }
}