<?php

namespace Jguillaumesio\PhpMercureHub\Response;

use Jguillaumesio\PhpMercureHub\Utils\UtilsManager;
use Ubiquity\log\Logger;

class JSONMercureResponse implements MercureResponse
{

    public function generate($topic, $request)
    {
        $tmp = ['@id' => $topic->name];
        Logger::alert('fizebjnokl',json_encode($request));
        if(\array_key_exists('language',$request) && $request['language'] !== null){
            UtilsManager::setHeader('Content-language', $request['language']);
            $tmp['@context'] =  ['@language' => $request['language']];
        }
        return \json_encode($tmp, \JSON_UNESCAPED_UNICODE);
    }
}