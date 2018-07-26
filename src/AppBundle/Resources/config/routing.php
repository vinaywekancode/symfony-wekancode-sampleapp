<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('app_homepage', new Route('/', array(
    '_controller' => 'AppBundle:Default:index',
)));

return $collection;
