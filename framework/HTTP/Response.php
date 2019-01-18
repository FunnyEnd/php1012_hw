<?php

namespace Framework\HTTP;

class Response
{
    private static $content;
    private static $responseCode;
    private static $headers = [];

    public static function setContent(string $content): void
    {
        self::$content = $content;
    }

    public static function setResponseCode(int $responseCode): void
    {
        self::$responseCode = $responseCode;
    }

    public static function redirect($location): string
    {
        array_push(self::$headers, "Location: {$location}");
        return '';
    }

    public static function send(): void
    {
        http_response_code(self::$responseCode);
        foreach (self::$headers as $h)
            header($h);

        echo self::$content;
    }
}