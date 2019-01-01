<?php

namespace Framework\Routing;

use Framework\HTTP\Request;
use InvalidArgumentException;
use UnexpectedValueException;
use ReflectionClass;
use ReflectionException;

class Route
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
        $this->logger = new \Zaine\Log(get_class($this));
    }

    public function isValid(): bool
    {
        if ($this->method != $this->getCurrentMethod())
            return false;

        $requestURI = $this->getURI();
        $reg = $this->convertToPreg($this->pattern);
        return (preg_match($reg, $requestURI) == 0) ? false : true;
    }

    public function executeController(Request $request): string
    {
        try {
            $controllerInfo = $this->getControllerData();
            $controller = (new ReflectionClass($controllerInfo['class']))->newInstance();

            if ($controller->methodHasRequestParam($controllerInfo['method'])) {
                $request->setGetData($this->getRequestParam());
                return $controller->callMethod($controllerInfo['method'], [$request]);
            } else {
                return $controller->callMethod($controllerInfo['method'], []);
            }
        } catch (ReflectionException $e) {
            $this->logger->error($e->getMessage());
        } catch (UnexpectedValueException $uve) {
            $this->logger->error($uve->getMessage());
        }
        return "";
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

    private function getRequestParam(): array
    {
        $requestURI = $this->getURI();
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
            throw new InvalidArgumentException("dataArray and keyArray has different count");

        foreach ($dataArray as $key => $item)
            $result[$keyArray[$key]] = $item;

        return $result;
    }

    private function getCurrentMethod(): string
    {
        // TODO: rewrite to $_SERVER['REQUEST_METHOD']
        $curMethod = 'get';
        if (isset($_POST['__method'])) {
            $curMethod = $_POST['__method'];
        } else if (count($_POST) > 0) {
            $curMethod = 'post';
        }
        return $curMethod;
    }

    private function getURI(): string
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strlen($requestURI) > 1 && (ord($requestURI[strlen($requestURI) - 1]) === ord('/')))
            $requestURI = substr($requestURI, 0, strlen($requestURI) - 1);
        return $requestURI;
    }
}
