<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

class AuthorizationManager
{

    public function checkAuthorization($request){
        $headersMethod = new HeadersAuthorization();
        $cookiesMethod = new CookiesAuthorization();
        $queryParamsMethod = new QueryParamsAuthorization();

        $headersMethod->setNextHandler($cookiesMethod);
        $cookiesMethod->setNextHandler($queryParamsMethod);

        $jwt = $headersMethod->getJWT($request);
        return (JWTManager::getInstance())->checkJWS($jwt);
    }

}