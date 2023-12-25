<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

use Ahc\Jwt\JWT;
use Ahc\Jwt\JWTException;
use Jguillaumesio\PhpMercureHub\Config;

class JWTManager {

    private static $instance;
    private $jwt;
    private $jwtConfig = [
        'maxAge' => 3600,
        'leeway' => 10,
    ];
    private $allowedJWTAlgorithm = ['HS256', 'HS384', 'HS512', 'RS256', 'RS384', 'RS512'];

    private function __construct() {
        $this->jwt = $this->generateJWT();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function base64url_decode($data) {
        $base64 = strtr($data, '-_', '+/');
        return base64_decode($base64);
    }

    private function base64url_encode($data) {
        $base64 = base64_encode($data);
        return strtr($base64, '+/', '-_');
    }

    private function checkJWTConfigValidity($config){
        return \array_key_exists('jwt',$config) &&
            \array_key_exists('algo',$config['jwt']) &&
            \array_key_exists('secret',$config['jwt']) &&
            \in_array($config['jwt']['algo'], $this->allowedJWTAlgorithm) &&
            \file_exists($config['jwt']['secret']);
    }

    private function generateJWT(){
        $config = new Config();
        if(!$this->checkJWTConfigValidity($config)){
            throw new \Error('INVALID_OR_MISSING_JWT_CONFIGURATION');
        }

        $key = (substr($config['jwt']['algo'], 0, 2) === 'RS')
            ? \openssl_pkey_new([
                'digest_alg' => 'sha256',
                'private_key_bits' => 1024,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ])
            : $config['jwt']['secret'];
        return new JWT($key, $config['jwt']['algo'], $this->jwtConfig['maxAge'], $this->jwtConfig['leeway']);
    }

    public function generateJWS($payload, $header){
        $token = $this->jwt->encode($payload, $header);
        $tokenParts = explode('.', $token);
        return [
            'header' => json_decode($this->base64url_decode($tokenParts[0]), true),
            'payload' => json_decode($this->base64url_decode($tokenParts[1]), true),
            'signature' => $tokenParts[2],
        ];
    }

    public function checkJWS($jws){
        try{
            $header = $this->base64url_encode($jws['header']);
            $payload = $this->base64url_encode($jws['payload']);
            $jwt = "$header.$payload.{$jws['signature']}";
            return $this->jwt->decode($jwt);
        }catch(JWTException $e){
            return false;
        }
    }
}