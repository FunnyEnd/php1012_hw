<?php

namespace App;

use App\Transport\TransportInterface;
use App\TemplateEngine\View;
use Exception;
use InvalidArgumentException;

class Sender
{
  private $senderImplementation = null;

  public function __construct(TransportInterface $transportInterface)
  {
    $this->senderImplementation = $transportInterface;
  }

  public function sendMessage($event, $user)
  {
    try {
      switch ($event) {
        case 'register' :
          {
            $dataArray = array(
                'firstName' => $user['firstName'],
                'lastName' => $user['lastName']
            );
            $messageText = View::render('template', $dataArray);
            $subject = "Register";
            break;
          }
        default:
          {
            throw new InvalidArgumentException('Invalid event value.');
            break;
          }
      }

      return $this->senderImplementation->send(
          $subject,
          $messageText,
          [$user['email'] => $user['firstName'] . ' ' . $user['lastName']]
      );
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }
}