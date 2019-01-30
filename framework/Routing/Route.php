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
    private $validator;

    public function __construct(
        string $method,
        string $pattern,
        string $controller,
        array $controllerParam = [],
        $validator = null
    ) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->controllerParam = $controllerParam;
        $this->validator = $validator;
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
        $replacements[] = '([^/]+)';

        $search[] = '/\\//';
        $replacements[] = '\\/';

        return '/^' . preg_replace($search, $replacements, $pattern) . '$/';
    }

    public function executeController(Request $request): string
    {
        // set get data to request object
        $request->setGetData($this->getRequestParam());

        // check validator
        if ($this->validator != null) {
            $validatorData = $this->getValidatorData();
            $result = $this->callClassMethod($validatorData['class'], $validatorData['method']);

            if ($result != '') {
                return $result;
            }
        }

        $controllerInfo = $this->getControllerData();

        return $this->callClassMethod($controllerInfo['class'], $controllerInfo['method']);
    }

    private function getControllerData(): array
    {
        $data = explode('::', $this->controller);

        return array(
            'class' => $data[0],
            'method' => $data[1]
        );
    }

    private function getValidatorData(): array
    {
        $data = explode('::', $this->validator);

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

    private function callClassMethod($class, $method)
    {
        try {
            $refClass = new ReflectionClass($class);
            $validatorConstructParams = [];

            // get __construct method params
            if ($refClass->hasMethod('__construct')) {
                $params = $refClass->getConstructor()->getParameters();
                foreach ($params as $p) {
                    array_push($validatorConstructParams, Dispatcher::get($p->getClass()->name));
                }
            } else {
                throw new UnexpectedValueException("Oh, method `__construct` don`t exit at " .
                    "{$class}.");
            }

            $validatorMethodParam = [];
            // get call method method params
            if ($refClass->hasMethod($method)) {
                $params = $refClass->getMethod($method)->getParameters();
                foreach ($params as $p) {
                    array_push($validatorMethodParam, Dispatcher::get($p->getClass()->name));
                }
            } else {
                throw new UnexpectedValueException("Oh, method `{$method}` don`t exit at " .
                    "{$class}.");
            }

            $instance = $refClass->newInstanceArgs($validatorConstructParams);

            return $instance->callMethod($method, $validatorMethodParam);
        } catch (ReflectionException $e) {
            $this->logger->error($e->getMessage());
        } catch (UnexpectedValueException $e) {
            $this->logger->error($e->getMessage());
        }

        return '';
    }
}
