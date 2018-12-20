<?php

namespace Framework\Router;

use Framework\HTTP\Request;
use ReflectionClass;

class Rout
{
    private $pattern;
    private $controller;
    private $method;

    public function __construct(string $method, string $pattern, string $controller)
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->controller = $controller;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    private function getControllerData(): array
    {
        $data = explode('::', $this->controller);
        return array(
                'class' => $data[0],
                'method' => $data[1]
        );
    }

    public function isEqCurRequest(): bool
    {
        if ($this->method != Request::getCurrentMethod())
            return false;

        $requestURI = Request::getRequestURI();
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
        try {

            $controllerData = $this->getControllerData();
            $className = $controllerData['class'];
            $method = $controllerData['method'];
            $customRequest = null;
            $reflectorRequest = new ReflectionClass($className);
            $reflectorMethodParam = $reflectorRequest->getMethod($method)->getParameters();
            $paramMethodArray = array();
            foreach ($reflectorMethodParam as $p)
                array_push($paramMethodArray, $p->getClass()->name);

            if (count($paramMethodArray) != 0) {
                $requestClass = $paramMethodArray[0];
                $customRequest = new $requestClass();
                $customRequest->setGetData($this->getDataFromRequest());

                if (!$customRequest->valid())
                    header('Location: /');
            }

            $controller = new $className();
            if ($customRequest == null)
                return $controller->$method();
            else
                return $controller->$method($customRequest);

        } catch (\ReflectionException $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    private function getDataFromRequest(): array
    {
        $requestURIArray = Request::getRequestURIArray();
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


}