<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

class AuthorizationManager
{
    private static $instance;

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getJWTPayload($request){
        $headersMethod = new HeadersAuthorization();
        $cookiesMethod = new CookiesAuthorization();
        $queryParamsMethod = new QueryParamsAuthorization();

        $headersMethod->setNextHandler($cookiesMethod);
        $cookiesMethod->setNextHandler($queryParamsMethod);

        $jwt = $headersMethod->getJWT($request);
        return (JWTManager::getInstance())->getJWTPayload($jwt);
    }

}