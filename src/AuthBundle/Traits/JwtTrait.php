<?php

namespace AuthBundle\Traits;

use Carbon\Carbon;
use Firebase\JWT\JWT;

trait JwtTrait
{
    static $key = "Secure Key";
    public function createJwtToken(array $data){
        $key = static::$key;
        $token = [
            "iss" => "",
            "aud" => "",
            "iat" => Carbon::now()->timestamp,
            "nbf" => Carbon::now()->timestamp,
            "data" => $data
        ];

       return JWT::encode($token, $key);
    }

    public function decodeToken($token){
        return JWT::decode($token, static::$key, ['HS256']);
    }
}