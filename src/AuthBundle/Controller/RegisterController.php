<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use AppBundle\Traits\JsonResponseTrait;
use AuthBundle\Traits\JwtTrait;
use AuthBundle\Validators\FormValidator;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;

class RegisterController extends Controller
{
    use JsonResponseTrait, JwtTrait;

    private $userRepo;

    public function indexAction(Request $request)
    {
        $validationMessages = FormValidator::validate($request);
        if(count($validationMessages) > 0){
            return $this->errorResponse($validationMessages, 422);
        }

        $this->userRepo = $this->getDoctrine()->getRepository(User::class);
        $user =  $this->userRepo->findOneBy([
            "email" => $request->request->get('email')
        ]);
        if($user){
            return $this->errorResponse('Email already registered', 422);
        }

        $encoder = $this->container->get('security.password_encoder');
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setRoles($request->request->get('role', 'ROLE_USER'));
        $user->setCreatedAt(Carbon::now());
        $user->setUpdatedAt(Carbon::now());
        $hashedPassword = $encoder->encodePassword($user, $request->request->get('password'));
        $user->setPassword($hashedPassword);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->successResponse(["message" => "User created successfully"], 200);
    }

}
