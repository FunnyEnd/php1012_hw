<?php

namespace App\Transport;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Exception;

class TransportSwiftMailer implements TransportInterface
{
    private $host;
    private $port;
    private $emailFrom;
    private $pass;
    private $encryption;
    private $name;

    public function __construct()
    {
        $config = require 'config.php';
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->emailFrom = $config['emailFrom'];
        $this->name = $config['name'];
        $this->pass = $config['pass'];
        $this->encryption = $config['encryption'];
    }

    public function send($subject, $message, $recipientParam)
    {
        try {
            $transport = (new Swift_SmtpTransport($this->host, $this->port))
                    ->setUsername($this->emailFrom)
                    ->setPassword($this->pass)
                    ->setEncryption($this->encryption);

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message($subject))
                    ->setFrom([$this->emailFrom => $this->name])
                    ->setTo($recipientParam)
                    ->addPart($message, 'text/html');

            $result = $mailer->send($message);
        } catch (Exception $e) {
            die($e->getMessage());
        }

        if ($result != 1)
            throw new Exception('Swift_Mailer error with code $result.');
    }
}