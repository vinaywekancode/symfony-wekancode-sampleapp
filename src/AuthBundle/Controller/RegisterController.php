<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use AppBundle\Traits\JsonResponseTrait;
use AuthBundle\Traits\JwtTrait;
use AuthBundle\Validators\RegistrationFormValidator;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RegisterController extends Controller
{
    use JsonResponseTrait, JwtTrait;

    private $userRepo;

    /**
     * @ApiDoc(
     *      resource=false,
     *      description="To register user",
     *      parameters={
     *            {"name"="name", "dataType"="string", "required"=true, "description"="name field"},
     *            {"name"="email", "dataType"="string", "required"=true, "description"="email field"},
     *            {"name"="password", "dataType"="string", "required"=true, "description"="password field"},
     *            {"name"="role", "dataType"="string", "required"=false, "description"="Role is optional. default is ROLE_USER"}
     *      },
     *      statusCodes={
     *         200="Returned when successful",
     *         422="Returned when validation fails",
     *     }
     * )
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $validationMessages = RegistrationFormValidator::validate($request);
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
