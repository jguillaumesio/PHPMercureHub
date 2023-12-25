<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

class HeadersAuthorization extends AbstractAuthorization  implements AuthorizationMethodInterface
{

    public function getJWT($request)
    {
        if(
            !\array_key_exists('headers', $request) ||
            !\array_key_exists('authorization', $request['headers']) ||
            \strpos($request['headers']['authorization'], 'Bearer ') === -1
        ){
            return $this->next();
        }
        return \str_replace('Bearer ','', $request['headers']['authorization']);
    }
}