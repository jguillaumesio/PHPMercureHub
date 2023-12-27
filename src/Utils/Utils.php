<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

class Utils extends AbstractUtils implements UtilsInterface {

    public static function setHeader($key, $value, $replace = true){
        if(\headers_sent()){
            throw new \Error('HEADERS_ALREADY_SENT');
        }
        header("$key: $value", $replace);
    }

    public static function setHeaders($keyValues, $replace){
        //This allow to only check one time for headers_sent instead or n times
        if(\headers_sent()){
            throw new \Error('HEADERS_ALREADY_SENT');
        }
        foreach (\array_filter($keyValues, fn($e) => \array_key_exists('key', $e) && \array_key_exists('value', $e)) as $e){
            header("{$e['key']}: {$e['value']}", $replace);
        }
    }

    public static function getHeaders(){
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[strtolower(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5))))))] = $value;
            }
        }
        return $headers;
    }

    public static function getQueryParams(){
        if(!\array_key_exists('QUERY_STRING', $_SERVER) || $_SERVER['QUERY_STRING'] === ''){
            return [];
        }
        $queryParams = [];
        foreach (explode('&', $_SERVER['QUERY_STRING']) as $param) {
            list($name, $value) = array_pad(explode('=', $param, 2), 2, null);
            $name = urldecode($name);
            $value = urldecode($value);
            if (array_key_exists($name, $queryParams)) {
                if (!is_array($queryParams[$name])) {
                    $queryParams[$name] = [$queryParams[$name]];
                }
                $queryParams[$name][] = $value;
            } else {
                $queryParams[$name] = $value;
            }
        }
        return $queryParams;
    }

    public static function getCookies(){
        return $_SERVER['HTTP_COOKIE'];
    }
}