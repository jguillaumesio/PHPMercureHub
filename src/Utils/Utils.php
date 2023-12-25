<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

class Utils extends AbstractUtils implements UtilsInterface {

    public function setHeader($key, $value, $replace = true){
        if(\headers_sent()){
            throw new \Error('HEADERS_ALREADY_SENT');
        }
        header("$key: $value", $replace);
    }

    public function setHeaders($keyValues, $replace){
        //This allow to only check one time for headers_sent instead or n times
        if(\headers_sent()){
            throw new \Error('HEADERS_ALREADY_SENT');
        }
        foreach (\array_filter($keyValues, fn($e) => \array_key_exists('key', $e) && \array_key_exists('value', $e)) as $e){
            header("{$e['key']}: {$e['value']}", $replace);
        }
    }

    public function getHeaders(){
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[strtolower(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5))))))] = $value;
            }
        }
        return $headers;
    }

    public function getQueryParams(){
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[strtolower(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5))))))] = $value;
            }
        }
        return $headers;
    }

    public function getCookies(){
        return $_SERVER['HTTP_COOKIE'];
    }
}