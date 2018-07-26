<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$loginRoute = new Route(
    '/login',
    ['_controller' => 'AuthBundle:Login:login'],
    [], [], '', ['http', 'https'], ['POST']);

$registerRoute = new Route(
    '/register',
    ['_controller' => 'AuthBundle:Register:index'],
    [], [], '', ['http', 'https'], ['POST']);


$collection = new RouteCollection();
$collection->add('login', $loginRoute);
$collection->add('register', $registerRoute);
$collection->addPrefix('auth');

return $collection;