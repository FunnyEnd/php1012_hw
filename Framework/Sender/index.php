<?php
require __DIR__ . "/config.php";
require __DIR__ . '/vendor/autoload.php';

use App\Sender;
use App\Transport\TransportSwiftMailer;

$transportSwiftMailer = new TransportSwiftMailer();
$sender = new Sender($transportSwiftMailer);

$user = array(
    'firstName' => 'Alex',
    'lastName' => 'Korneinko',
    'email' => 'intersection1999@gmail.com'
);

$sender->sendMessage(
    'register',
    $user
);
