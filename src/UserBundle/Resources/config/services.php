<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;

/*

$container->setDefinition(
    'user.example',
    new Definition(
        'UserBundle\Example',
        array(
            new Reference('service_id'),
            "plain_value",
            new Parameter('parameter_name'),
        )
    )
);

*/

//$definition = new Definition();

//$definition
//    ->setAutowired(true)
//    ->setAutoconfigured(true)
//    ->setPublic(false);
//
//
//$this->registerClasses($definition, 'UserBundle\\', '../*', '../../{Entity,Repository}');