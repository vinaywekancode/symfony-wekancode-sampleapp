<?php

namespace UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
Use UserBundle\Entity\User;

trait Policy
{
    public function checkAccess(array $decodedToken, User $user = null)
    {
       
       if($decodedToken["role"] === "ROLE_ADMIN"){
           return true;
       }

       if($user->getId() !== $decodedToken["id"]){
           return false;
       }

       return true;
    }
}