<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

class CookiesAuthorization extends AbstractAuthorization implements AuthorizationMethodInterface
{

    public function authorize($request)
    {
       if(!\array_key_exists('cookie', $request) || !array_key_exists('authorization', $request['cookie'])){
           return $this->next();
       }
       return
    }
}