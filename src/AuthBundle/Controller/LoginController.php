<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Traits\JsonResponseTrait;
use AuthBundle\Validators\FormValidator;
use AuthBundle\Traits\JwtTrait;

class LoginController extends Controller
{
    use JsonResponseTrait, JwtTrait;

    private $userRepo;

    public function loginAction(Request $request)
    {
        $validationMessages = FormValidator::validate($request);
        if(count($validationMessages) > 0){
            return $this->errorResponse($validationMessages, 422);
        }

        //Check if user exists in database and verify the password
        $this->userRepo = $this->getDoctrine()->getRepository(User::class);
        $user =  $this->userRepo->findOneBy([
                "email" => $request->request->get('email')
            ]);
        if(!$user){
            return $this->errorResponse('No user found', 422);
        }
        $encoder = $this->container->get('security.password_encoder');
        if(!$encoder->isPasswordValid($user, $request->request->get('password'))){
            return $this->errorResponse('Invalid password', 401);
        }

        $token = $this->createJwtToken([
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "role" => $user->getRoles()
        ]);
        return $this->successResponse(compact('token'), 200);
    }

}
