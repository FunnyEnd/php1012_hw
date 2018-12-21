<?php

namespace Framework\Router;

use Framework\HTTP\Request;
use Framework\Logger\FileLogger;
use Framework\Logger\Log;
use http\Exception\UnexpectedValueException;
use ReflectionClass;

class Rout
{
    private $pattern;
    private $controller;
    private $method;
    private $logger;

    public function __construct(string $method, string $pattern, string $controller)
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->controller = $controller;

        $fileLogger = new FileLogger();
        $this->logger = new Log($fileLogger);
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
            $controller = new $className();
            if (!method_exists($controller, $method))
                throw new UnexpectedValueException('Invalid method name.');

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
            // TODO: rewrite to call_user_func_array()

            if ($customRequest == null)
                return $controller->$method();
            else
                return $controller->$method($customRequest);

        } catch (\ReflectionException $e) {
            $this->logger->error($e->getMessage());
            die();
        } catch (UnexpectedValueException $uve) {
            $this->logger->error($uve->getMessage());
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
