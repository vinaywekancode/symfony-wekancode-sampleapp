<?php

namespace UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
Use UserBundle\Entity\User;

trait Policy
{
    public function canView(array $decodedToken, User $user = null)
    {

       if($decodedToken["role"] === "ROLE_ADMIN"){
           return true;
       }

       if($user->getId() !== $decodedToken["id"]){
           return false;
       }

       return true;
    }

    public function canViewAllUsers(array $decodedToken)
    {
        return $decodedToken["role"] === "ROLE_ADMIN";
    }
}