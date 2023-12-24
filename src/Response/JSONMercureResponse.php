<?php

namespace Jguillaumesio\PhpMercureHub\Response;

class JSONMercureResponse implements MercureResponse
{

    public function generate($resource, $values, $request)
    {
        $tmp = \array_merge($values, ['@id' => $resource]);
        if(\array_key_exists('language',$request) && $request['language'] !== null){
            $tmp['@context'] =  ['@language' => $request['language']];
        }
        return \json_encode($tmp, \JSON_UNESCAPED_UNICODE);
    }
}