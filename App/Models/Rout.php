<?php

namespace App\Models;

class Rout
{
    private $pattern;
    private $controller;

    public function __construct(string $pattern, string $controller)
    {
        $this->pattern = $pattern;
        $this->controller = $controller;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function isEqCurRequest(): bool
    {
        $requestURI = $this->getRequestURI();
        $requestURIArray = explode('/', $requestURI);
        $patternArray = $this->patternToArray();
        if (count($patternArray) !== count($requestURIArray))
            return false;

        foreach ($patternArray as $key => $patternVal) {
            if ($patternVal != '' && (ord($patternVal[0]) != ord('#'))) {
                if ($requestURIArray[$key] != $patternVal)
                    return false;
            }
        }

        return true;
    }

    public function executeController(): void
    {
        $getData = $this->getDataFromRequest();
        $data = explode('::', $this->controller);
        $className = $data[0];
        $method = $data[1];
        $controller = new $className();
        if (count($getData) == 0)
            $controller->$method();
        else
            $controller->$method($getData);
    }

    private function getDataFromRequest(): array
    {
        $requestURI = $this->getRequestURI();
        if (!$this->isEqCurRequest($requestURI))
            return array();

        $requestURIArray = explode('/', $requestURI);
        $patternArray = $this->patternToArray();
        $res = array();

        foreach ($patternArray as $key => $patternVal) {
            if ($patternVal != '' && (ord($patternVal[0]) == ord('#'))) {
                $resKey = substr($patternVal, 1, strlen($patternVal));
                $res[$resKey] = $requestURIArray[$key];
            }
        }

        return $res;
    }

    private function patternToArray(): array
    {
        return explode('/', $this->pattern);
    }

    public function getRequestURI(): string
    {
        $requestURI = $_SERVER['REQUEST_URI'];

        if (strlen($requestURI) > 1 && (ord($requestURI[strlen($requestURI) - 1]) === ord('/')))
            $requestURI = substr($requestURI, 0, strlen($requestURI) - 1);

        return $requestURI;

    }
}