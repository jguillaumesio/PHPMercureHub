<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

use Jguillaumesio\PhpMercureHub\Response\HTMLMercureResponse;
use Jguillaumesio\PhpMercureHub\Response\JSONMercureResponse;

class AbstractUtils {

    public static $availableResponseTypes = [
        'text/html' => [
            'type' => 'html',
            'encoder' => HTMLMercureResponse::class
        ],
        'application/ld+json' => [
            'type' => 'jsonld',
            'encoder' => JSONMercureResponse::class
        ]];

    public static function generateResponse($resource, $values, $request, $type){
        if(!array_key_exists($type, self::$availableResponseTypes)){
            throw new \Error('INVALID_CONTENT_TYPE_OR_RESPONSE_TYPE');
        }
        return (new self::$availableResponseTypes[$type]['encoder']())->generate($resource, $values, $request);
    }

}