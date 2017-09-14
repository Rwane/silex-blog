<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

/*FRONT*/

$app->get('/', 'index.controller:indexAction')
     ->bind('homepage');


$app->get('/rubriques/menu', 'category.controller:menuAction')
       ->bind('categories_menu');

$app->get('/rubrique/{id}', 'category.controller:indexAction')
    ->bind('category');

/*ADMIN*/

// crée un groupe de route
$admin = $app['controllers_factory'];
//toutes les url des routes créées par $admin sont préfixées par admin/
$app->mount('/admin', $admin);

$admin->get('/rubriques', 'admin.category.controller:listAction')
        ->bind('admin_categories');
       

/*
 * la ruote contient un formulaire POST
 * elle est definie en match pour accepter GET et POST
 */
$admin->match('/rubrique/edition/{id}', 'admin.category.controller:editAction')
        ->value('id', null)// is est optionnel est vaut null par défaut
        ->bind('admin_category_edit');



/*
 * 
 */
$admin->get('/rubriques/suppression/{id}', 'admin.category.controller:deleteAction')
        ->assert('id', '\d+')// force id à etre un nombre
        ->bind('admin_category_delete');


/*
 * Creer la partie admin pour les articles:
 * creer le controleur Admin\ArticleController qui hérite de ControllerAbstract
 * le définir en service dans app.php
 * y ajouter la méthode listAction() qui va rendre la vue admin/article/list.html
 * créer la vue
 * créer la route qui pointe sur l'action
 * ajouter un lien qui pointe vers cette route dans la navbar admin
 * creer l'entity article et le repository ArticleRepository qui hérite de RepositoryAbstract
 * déclarer le repository en service dans app.php
 * remplir la méthode ListAction() du controlleur en utilisant ArticleRepository
 * faire l'affiche en tableau HTML dans la vue
 * 
 * ajouter les méthodes editAction() et deleteaction() avec leurs routes et la vue d'édition
 * pour le formulaire d'édition:
 *         input text pour le titre
 *         textarea pour header et content
 */

$admin->get('/articles', 'admin.article.controller:listAction')
        ->bind('admin_articles');

$admin->get('/articles/suppression/{id}', 'admin.article.controller:deleteAction')
        ->assert('id', '\d+')// force id à etre un nombre
        ->bind('admin_article_delete');

$admin->match('/articles/edition/{id}', 'admin.article.controller:editAction')
        ->value('id', null)
        ->assert('id', '\d+')// force id à etre un nombre
        ->bind('admin_article_edit');



//route user

$app->match('/inscription','user.controller:registerAction')
        ->bind('register');



$app->match('/connexion', 'user.controller:loginAction')
     ->bind('login');

$app->match('/deconnexion', 'user.controller:logoutAction')
     ->bind('logout');

//route admin user
$admin->before(function () use ($app){
    if(!$app['user.manager']->isAdmin()){
        $app->abort(403, 'Accès refusé');
    }
});





$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
