<?php

namespace Framework\Logger;

class FileLogger implements LoggerInterface
{
    private $fileLog = 'log/error.log';

    private function logToFile(string $text)
    {
        file_put_contents($this->fileLog, $text, FILE_APPEND);
    }

    private function contextToString(array $context = array()): string
    {
        $string = "";

        foreach ($context as $c) {
            if (method_exists($c, "__toString"))
                $string .= $c->__toString() . PHP_EOL;
            else {
                ob_start();
                var_dump($c);
                $res = ob_get_clean();
                $string .= $res . PHP_EOL;
            }
        }
        return $string;
    }

    public function emergency($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Emergency] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function alert($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Alert] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function critical($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Critical] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function error($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Error] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function warning($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Warning] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function notice($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Notice] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function info($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Info] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function debug($message, array $context = array())
    {
        $date = date("H:m:s");
        $contextToString = "$date [Debug] " . $message . PHP_EOL . $this->contextToString($context);
        $this->logToFile($contextToString);
    }

    public function log($level, $message, array $context = array())
    {
        call_user_func_array(array($this, $level), array($message, $context));
    }
}