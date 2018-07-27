<?php

namespace AuthBundle\Validators;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use AppBundle\Traits\JsonResponseTrait;

class LoginFormValidator
{
    use JsonResponseTrait;

    public static function validate(Request $request){
        $errorMessage = [];

        if($request->getPathInfo() === "/auth/register"){
            $nameValidator = Validation::createValidator();
            $nameViolations = $nameValidator->validate($request->request->get('name'), [
                new Length(['min' => 3]),
                new NotBlank(),
            ]);
            if(count($nameViolations) > 0){
                array_push($errorMessage, ["name" => $nameViolations[0]->getMessage() ]);
            }
        }

        $emailValidator = Validation::createValidator();
        $emailViolations = $emailValidator->validate($request->request->get('email'), [
            new Length(['min' => 1]),
            new NotBlank(),
            new Email()
        ]);
        if(count($emailViolations) > 0){
            array_push($errorMessage, ["email" => $emailViolations[0]->getMessage()]);
        }

        $passwordValidator = Validation::createValidator();
        $passwordViolations = $passwordValidator->validate($request->request->get('password'), [
            new Length(['min' => 1]),
            new NotBlank(),
        ]);
        if(count($passwordViolations) > 0){
            array_push($errorMessage, ["password" => $passwordViolations[0]->getMessage()]);
        }

        return $errorMessage;
    }
}