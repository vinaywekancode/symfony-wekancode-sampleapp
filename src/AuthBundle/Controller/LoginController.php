<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Traits\JsonResponseTrait;
use AuthBundle\Validators\FormValidator;
use AuthBundle\Traits\JwtTrait;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LoginController extends Controller
{
    use JsonResponseTrait, JwtTrait;

    private $userRepo;

    /**
     * @ApiDoc(
     *      resource=false,
     *      description="To authenticate user",
     *      parameters={
     *            {"name"="email", "dataType"="string", "required"=true, "description"="email field"},
     *            {"name"="password", "dataType"="string", "required"=true, "description"="password field"}
     *      },
     *      statusCodes={
     *         200="Returned when successful",
     *         422="Returned when validation fails",
     *         401="Returned when credentials are wrong"
     *     }
     * )
     * @param Request $request
     * @return mixed
     */
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
