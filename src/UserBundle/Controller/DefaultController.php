<?php

namespace UserBundle\Controller;

use AuthBundle\Contracts\JwtAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Traits\JsonResponseTrait;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use UserBundle\Repository\UserRepository;
use UserBundle\Security\Policy;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class DefaultController extends Controller implements JwtAuthenticatedController
{
    use JsonResponseTrait, Policy;

    public function __construct()
    {
    }

    /**
     * @ApiDoc(
     *      resource=false,
     *      description="To list all Users",
     *      headers={
     *          {
     *              "name"="Authorization",
     *              "required"="true",
     *              "description"="Bearer token"
     *          }
     *      },
     *      statusCodes={
     *         200="Returned when successful",
     *         401="Returned when the user is not authorized",
     *     }
     * )
     * @param Request $request
     * @return mixed
     */
    public function allAction(Request $request)
    {
        if($this->canViewAllUsers((array) $request->request->get('decodedToken')) === false){
            return $this->errorResponse("Access denied", 401);
        }

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAllUsers();

        return $this->successResponse($users, 200);
    }

    /**
     * @ApiDoc(
     *      resource=false,
     *      description="To list details of specific User",
     *      headers={
     *          {
     *              "name"="Authorization",
     *              "required"="true",
     *              "description"="Bearer token"
     *          }
     *      },
     *      statusCodes={
     *         200="Returned when successful",
     *         401="Returned when the user is not authorized",
     *     }
     * )
     * @Route("/users/{id}")
     * @ParamConverter("get", class="UserBundle:User")
     * @param Request $request
     * @param User $user
     * @return JsonResponseTrait
     */
    public function showAction(Request $request, User $user){
        if($this->canView((array) $request->request->get('decodedToken'), $user) === false){
            return $this->errorResponse("Access denied", 401);
        }

        return $this->successResponse([
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ], 200);
    }
}
