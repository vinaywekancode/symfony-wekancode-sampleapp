<?php

namespace UserBundle\Controller;

use AuthBundle\Contracts\JwtAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Traits\JsonResponseTrait;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use UserBundle\Security\Policy;


class DefaultController extends Controller implements JwtAuthenticatedController
{
    use JsonResponseTrait, Policy;


    /**
     * @param Request $request
     * @return mixed
     */
    public function allAction(Request $request)
    {
        if($this->checkAccess((array) $request->request->get('decodedToken')) === false){
            throw $this->createAccessDeniedException("Access not allowed");
        }

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAllUsers();

        return $this->successResponse($users, 200);
    }

    /**
     * @Route("/users/{id}")
     * @ParamConverter("post", class="UserBundle:User")
     * @param Request $request
     * @param User $user
     * @return JsonResponseTrait
     */
    public function showAction(Request $request, User $user){
        if($this->checkAccess((array) $request->request->get('decodedToken'), $user) === false){
            throw $this->createAccessDeniedException("Access not allowed");
        }

        return $this->successResponse([
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ], 200);
    }
}
