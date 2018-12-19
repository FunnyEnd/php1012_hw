<?php

namespace Framework\HTTP;

class Response
{
    private static $content;
    private static $responseCode;

    public static function setContent(string $content): void
    {
        self::$content = $content;
    }

    public static function setResponseCode(int $responseCode): void
    {
        self::$responseCode = $responseCode;
    }

    public static function send(): void
    {
        http_response_code(self::$responseCode);
        echo self::$content;
    }
}