<?php

namespace Framework\Routing;

use Framework\Dispatcher;
use Framework\HTTP\Request;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

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
        if ($this->method != $this->getCurrentMethod()) {
            return false;
        }

        $requestURI = $this->getURI();
        $reg = $this->convertToPreg($this->pattern);

        return (preg_match($reg, $requestURI) == 0) ? false : true;
    }

    private function getCurrentMethod(): string
    {
        $curMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($_POST['__method'])) {
            $curMethod = $_POST['__method'];
        }

        return $curMethod;
    }

    private function getURI(): string
    {
        $requestURI = $_SERVER['REQUEST_URI'];

        if (strlen($requestURI) > 1 && (ord($requestURI[strlen($requestURI) - 1]) === ord('/'))) {
            $requestURI = substr($requestURI, 0, strlen($requestURI) - 1);
        }

        return $requestURI;
    }

    private function convertToPreg(string $pattern): string
    {
        $search = array();
        $replacements = array();

        $search[] = '/:d/';
        $replacements[] = '([[:digit:]]+)';

        $search[] = '/:any/';
        $replacements[] = '(.+)';

        $search[] = '/\\//';
        $replacements[] = '\\/';

        return '/^' . preg_replace($search, $replacements, $pattern) . '$/';
    }

    public function executeController(Request $request): string
    {
        try {
            $controllerInfo = $this->getControllerData();
            $class = new ReflectionClass($controllerInfo['class']);

            $constructParams = [];
            $methodParam = [];

            // get __construct method params
            if ($class->hasMethod('__construct')) {
                $params = $class->getConstructor()->getParameters();
                foreach ($params as $p) {
                    array_push($constructParams, Dispatcher::get($p->getClass()->name));
                }
            } else {
                throw new UnexpectedValueException(
                        "Oh, method `__construct` don`t exit at {$controllerInfo['class']}.");
            }

            // get call method method params
            if ($class->hasMethod($controllerInfo['method'])) {
                $params = $class->getMethod($controllerInfo['method'])->getParameters();
                foreach ($params as $p) {
                    array_push($methodParam, Dispatcher::get($p->getClass()->name));
                }
            } else {
                throw new UnexpectedValueException(
                        "Oh, method `{$controllerInfo['method']}` don`t exit at {$controllerInfo['class']}.");
            }

            // set get data to request object
            $request->setGetData($this->getRequestParam());

            // create controller instance
            $controller = $class->newInstanceArgs($constructParams);

            // returned result of the method invoked by the controller
            return $controller->callMethod($controllerInfo['method'], $methodParam);

        } catch (ReflectionException $e) {
            $this->logger->error($e->getMessage());
        } catch (UnexpectedValueException $e) {
            $this->logger->error($e->getMessage());
        }

        return '';
    }

    private function getControllerData(): array
    {
        $data = explode('::', $this->controller);

        return array(
                'class' => $data[0],
                'method' => $data[1]
        );
    }

    private function getRequestParam(): array
    {
        $requestURI = $this->getURI();
        $reg = $this->convertToPreg($this->pattern);
        if (preg_match($reg, $requestURI, $matches) != 0) {
            if (count($matches) == 1) {
                return [];
            }
        }

        array_splice($matches, 0, 1);

        return $this->arrayMergeDataAndKey($matches, $this->controllerParam);
    }

    private function arrayMergeDataAndKey(array $dataArray, array $keyArray): array
    {
        $result = array();

        if (count($dataArray) != count($keyArray)) {
            throw new InvalidArgumentException('dataArray and keyArray has different count');
        }

        foreach ($dataArray as $key => $item) {
            $result[$keyArray[$key]] = $item;
        }

        return $result;
    }
}
