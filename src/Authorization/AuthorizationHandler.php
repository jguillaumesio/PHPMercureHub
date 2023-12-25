<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

use Ahc\Jwt\JWT;

class AuthorizationHandler
{
    public function generateJWS(){
        $jwt = new JWT('secret', 'HS256', 3600, 10);
    }

    public function checkJWS(){

    }

    public function checkAuthorization(){

    }

}