<?php

namespace AppBundle\Traits;


trait JsonResponseTrait
{
    public function successResponse($data, $code){
        return  $this->json(compact('data'), $code);
    }

    public function errorResponse($message, $code, $data = null){
        return $this->json([
            "errors" => compact('data', 'message')
        ], $code);
    }
}