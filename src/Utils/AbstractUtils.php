<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

use Jguillaumesio\PhpMercureHub\Response\HTMLMercureResponse;
use Jguillaumesio\PhpMercureHub\Response\JSONMercureResponse;

class AbstractUtils {

    public static function getAvailableResponseTypes(){
        return [
            'text/html' => [
                'type' => 'html',
                'encoder' => HTMLMercureResponse::class
            ],
            'application/ld+json' => [
                'type' => 'jsonld',
                'encoder' => JSONMercureResponse::class
            ]];
    }

    public static function generateResponse($topic, $request){
        $availableResponseTypes = self::getAvailableResponseTypes();
        $type = $request['response_type'] ?? '';
        if(!array_key_exists($type, $availableResponseTypes)){
            throw new \Error('INVALID_CONTENT_TYPE_OR_RESPONSE_TYPE');
        }
        return (new $availableResponseTypes[$type]['encoder']())->generate($topic, $request);
    }

}