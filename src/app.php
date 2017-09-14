<?php

use Controller\Admin\ArticleController;
use Controller\Admin\CategoryController;
use Controller\IndexController;
use Controller\UserController;
use Repository\ArticleRepository;
use Repository\CategoryRepository;
use Repository\UserRepository;
use Service\UserManager;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    //le UserManager est accessible dans le twig globale user_manager
    $twig->addGlobal('user_manager', $app['user.manager']);
    
    return $twig;
});


/*
 * ajout de doctrine DBAL ($app['db])
 * necessite d'avoir exécuté:
 * composer require "doctrine/dbal:~2.2"
 * en ligne de commande dans le projet
 */




$app->register (
        new DoctrineServiceProvider(),
        [
            'db.options' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'dbname' => 'silex-blog',
                'user' => 'root',
                'password' => '',
                'charset' =>'utf8'                     
            ]
        ]
    );


//gestionnaire de sessions de symfony

$app->register(new SessionServiceProvider());




/* Controllers */

$app['index.controller'] = function () use ($app){
    return new IndexController($app);
};

/*admin*/

$app['admin.category.controller'] = function () use ($app){
    return new CategoryController($app);
};

$app['category.controller'] = function () use ($app){
    return new Controller\CategoryController($app);
};



/* repositories*/

$app['category.repository'] = function () use ($app){
    return new CategoryRepository($app['db']);
};



/*article app*/

/*admin article*/

$app['admin.article.controller'] = function () use ($app){
    return new ArticleController($app);
};

/* repository article*/

$app['article.repository'] = function () use ($app){
    return new ArticleRepository($app['db']);
};



//USER APP

/*
 * user repository
 */

$app['user.repository'] = function () use ($app){
    return new UserRepository($app['db']);
};

/*
 * user controller
 */
$app['user.controller'] = function () use ($app){
    return new UserController($app);
};

//user manager
 $app['user.manager'] = function () use ($app){
    return new UserManager($app['session']);
 };

 /**
  * user login
  */
 
 

return $app;
