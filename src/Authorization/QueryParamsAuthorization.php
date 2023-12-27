<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

class QueryParamsAuthorization extends AbstractAuthorization  implements AuthorizationMethodInterface
{

    public function getJWT($request)
    {
        if(!\array_key_exists('query_params', $request) || !array_key_exists('authorization', $request['query_params'])){
            return $this->next($request);
        }
        return $request['query_params']['authorization'];
    }
}