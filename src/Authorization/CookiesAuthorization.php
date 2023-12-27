<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

use Jguillaumesio\PhpMercureHub\Config;

class CookiesAuthorization extends AbstractAuthorization implements AuthorizationMethodInterface
{

    public function getJWT($request)
    {
        $config = Config::getConfig();
       if(!\array_key_exists('auth_cookie_name', $config) || !\array_key_exists('cookie', $request) || !array_key_exists($config['auth_cookie_name'], $request['cookie'])){
           return $this->next($request);
       }
       return $request['cookie'][$config['auth_cookie_name']];
    }
}