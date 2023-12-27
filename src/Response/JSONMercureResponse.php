<?php

namespace Jguillaumesio\PhpMercureHub\Response;

class JSONMercureResponse implements MercureResponse
{

    public function generate($topic, $request)
    {
        $tmp = ['@id' => $topic->name];
        if(\array_key_exists('language',$request) && $request['language'] !== null){
            $tmp['@context'] =  ['@language' => $request['language']];
        }
        return \json_encode($tmp, \JSON_UNESCAPED_UNICODE);
    }
}