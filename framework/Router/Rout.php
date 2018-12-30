<?php

namespace Framework\Router;

use Framework\HTTP\Request;
use Framework\Logger\FileLogger;
use Framework\Logger\Log;
use http\Exception\UnexpectedValueException;
use ReflectionClass;
use ReflectionException;

class Rout
{
    private $pattern;
    private $controller;
    private $method;
    private $logger;
    private $controllerParam;

    public function __construct(string $method, string $pattern, string $controller, array $controllerParam = array())
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->controllerParam = $controllerParam;
        $this->logger = new \Zaine\Log("Framework\Router");
    }

    public function isEqCurRequest(): bool
    {
        if ($this->method != Request::getCurrentMethod())
            return false;

        $requestURI = Request::getRequestURI();
        $reg = $this->convertToPreg($this->pattern);
        return (preg_match($reg, $requestURI) == 0) ? false : true;
    }

    public function executeController(Request $request): string
    {
        try {
            $controllerInfo = $this->getControllerData();
            $reflector = new ReflectionClass($controllerInfo['class']);
            // if method don`t exit at controller
            if (!$reflector->hasMethod($controllerInfo['method'])) {
                throw new UnexpectedValueException(
                        "Oh, method {$controllerInfo['method']}" .
                        " don`t exit at {$controllerInfo['class']}.");
            }
            $param = array();
            // if exist request param at controller method
            if (count($reflector->getMethod($controllerInfo['method'])->getParameters()) != 0) {
                $request->setGetData($this->getDataFromRequest());
                $param[] = $request;
            }
            return call_user_func_array(
                    array($reflector->newInstance(), $controllerInfo['method']),
                    $param
            );
        } catch (ReflectionException $e) {
            $this->logger->error($e->getMessage());
        } catch (UnexpectedValueException $uve) {
            $this->logger->error($uve->getMessage());
        }
    }

    private function getControllerData(): array
    {
        $data = explode('::', $this->controller);
        return array(
                'class' => $data[0],
                'method' => $data[1]
        );
    }

    private function convertToPreg(string $pattern): string
    {
        $search = array();
        $replacements = array();

        $search[] = '/:d/';
        $replacements[] = '([[:digit:]]+)';

        $search[] = '/\\//';
        $replacements[] = '\\/';

        return "/^" . preg_replace($search, $replacements, $pattern) . "$/";
    }

    private function getDataFromRequest(): array
    {
        $requestURI = Request::getRequestURI();
        $reg = $this->convertToPreg($this->pattern);
        if (preg_match($reg, $requestURI, $matches) != 0) {
            if (count($matches) == 1)
                return array();
        }
        unset($matches[0]);
        sort($matches);
        return $this->arrayMergeDataAndKey($matches, $this->controllerParam);
    }

    private function arrayMergeDataAndKey(array $dataArray, array $keyArray): array
    {
        $result = array();
        if (count($dataArray) != count($keyArray))
            throw new \InvalidArgumentException("dataArray and keyArray has different count");

        foreach ($dataArray as $key => $item)
            $result[$keyArray[$key]] = $item;

        return $result;
    }
}
