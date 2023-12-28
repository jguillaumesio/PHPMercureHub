<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

use Jguillaumesio\PhpMercureHub\Response\ResponseFactory;

class AbstractUtils {

    public static function getAvailableResponseTypes(){
        return [
            'text/html' => [
                'type' => 'html',
            ],
            'application/ld+json' => [
                'type' => 'jsonld',
            ]];
    }

    public static function generateResponse($topic, $request){
        $availableResponseTypes = self::getAvailableResponseTypes();
        $type = $request['response_type'] ?? '';
        if(!array_key_exists($type, $availableResponseTypes)){
            throw new \Error('INVALID_CONTENT_TYPE_OR_RESPONSE_TYPE');
        }
        $responseFactory = new ResponseFactory();
        return $responseFactory($type)->generate($topic, $request);
    }

}