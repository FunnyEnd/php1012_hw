<?php

namespace App;

spl_autoload_register(function ($class) {
    $includeClassName = str_replace('\\', '/', $class . ".php");
    require $includeClassName;
});

use App\Collection\RoutCollection;

const TEMPLATE_FOLDER = "Templates/";

RoutCollection::init();

require 'App/routes.php';

if (!RoutCollection::calCurrentRout()) {
    http_response_code(404);
}