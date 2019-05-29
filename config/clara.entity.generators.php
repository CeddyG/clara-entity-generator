<?php

/**
 * Default config values
 */
return [
    
    [
        'name'  => 'controller',
        'label' => 'Controller',
        'class' => CeddyG\ClaraEntityGenerator\Generator\ControllerGenerator::class
    ],
    [
        'name'  => 'model',
        'label' => 'Model',
        'class' => CeddyG\ClaraEntityGenerator\Generator\ModelGenerator::class
    ],
    [
        'name'  => 'repository',
        'label' => 'Repository',
        'class' => CeddyG\ClaraEntityGenerator\Generator\RepositoryGenerator::class
    ],
    [
        'name'  => 'request',
        'label' => 'Request',
        'class' => CeddyG\ClaraEntityGenerator\Generator\RequestGenerator::class
    ],
    [
        'name'  => 'event',
        'label' => 'Event',
        'class' => CeddyG\ClaraEntityGenerator\Generator\EventGenerator::class
    ],
    [
        'name'  => 'index',
        'label' => 'Index view',
        'class' => CeddyG\ClaraEntityGenerator\Generator\IndexGenerator::class
    ],
    [
        'name'  => 'form',
        'label' => 'Form view',
        'class' => CeddyG\ClaraEntityGenerator\Generator\FormGenerator::class
    ],
    [
        'name'  => 'traduction',
        'label' => 'Traduction',
        'class' => CeddyG\ClaraEntityGenerator\Generator\TraductionGenerator::class
    ],
    [
        'name'  => 'routeadmin',
        'label' => 'Route admin',
        'class' => CeddyG\ClaraEntityGenerator\Generator\RouteAdminGenerator::class
    ],
    [
        'name'  => 'routeapi',
        'label' => 'Route api',
        'class' => CeddyG\ClaraEntityGenerator\Generator\RouteApiGenerator::class
    ],
    [
        'name'  => 'navbar',
        'label' => 'Navbar',
        'class' => CeddyG\ClaraEntityGenerator\Generator\NavbarGenerator::class
    ],
    
];
