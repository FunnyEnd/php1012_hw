<?php

namespace Framework\Router\Models;

class Rout
{
    private $pattern;
    private $controller;
    private $requestClass;
    private $method;

    public function __construct(string $method, string $pattern, string $controller, string $requestClass = "")
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->requestClass = $requestClass;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getCurrentMethod(){
        $curMethod = 'get';

        if (isset($_POST['__method'])) {
            $curMethod = $_POST['__method'];
        } else if (count($_POST) > 0) {
            $curMethod = 'post';
        }

        return $curMethod;
    }

    public function isEqCurRequest(): bool
    {
        if ($this->method != $this->getCurrentMethod())
            return false;


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

    public function executeController(): string
    {
        $customRequest = null;

        if (!empty($this->requestClass)) {
            $requestClass = "App\\Request\\" . $this->requestClass;
            $customRequest = new $requestClass();
            $requestURI = $this->getRequestURI();
            $customRequest->setGetData($this->getDataFromRequest($requestURI));

            if (!$customRequest->valid())
                header('Location: /');
        }


        $data = explode('::', $this->controller);
        $className = $data[0];
        $method = $data[1];
        $controller = new $className();
        if ($customRequest == null)
            return $controller->$method();
        else
            return $controller->$method($customRequest);
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