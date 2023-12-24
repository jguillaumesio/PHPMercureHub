<?php

namespace Jguillaumesio\PhpMercureHub;

use Jguillaumesio\PhpMercureHub\Response\HTMLMercureResponse;
use Jguillaumesio\PhpMercureHub\Response\JSONMercureResponse;

class Utils implements UtilsInterface {

    public static $availableResponseTypes = [
        'text/html' => [
            'type' => 'html',
            'encoder' => HTMLMercureResponse::class
        ],
        'application/ld+json' => [
            'type' => 'jsonld',
            'encoder' => JSONMercureResponse::class
        ]];

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

    public function generateResponse($resource, $values, $request, $type){
        if(!array_key_exists($type, self::$availableResponseTypes)){
            throw new \Error('INVALID_CONTENT_TYPE_OR_RESPONSE_TYPE');
        }
        return (new self::$availableResponseTypes[$type]['encoder']())->generate($resource, $values, $request);
    }
}