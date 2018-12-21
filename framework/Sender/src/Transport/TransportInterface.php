<?php

namespace App\Transport;

interface TransportInterface
{
  public function send($subject, $message, $recipientParam);
}