<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$userListRoute = new Route(
    '/users',
    ['_controller' => 'UserBundle:Default:all'],
    [], [], '', ['http', 'https'], ['GET']);

$specificUserListRoute = new Route(
    '/users/{id}',
    ['_controller' => 'UserBundle:Default:show'],
    [], [], '', ['http', 'https'], ['GET']);

$collection = new RouteCollection();

$collection->add('user_list', $userListRoute);
$collection->add('list_specific_user', $specificUserListRoute);

return $collection;
