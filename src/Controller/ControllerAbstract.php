<?php
namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig_Environment;

abstract class ControllerAbstract {
 
  /**
   *
   * @var Application
   */
  protected  $app;
 
  /**
   *
   * @var Twig_Environment
   */
  protected $twig;
  
 /**
   *
   * @var Session
   */
  protected $session;
  
  /*
   * @param Application $app
   */
  public function __construct(Application $app) {
     
      $this->app = $app;
      $this->twig = $app['twig'];
      $this->session = $app['session'];
  }
 
 
  /**
   *  Affiche une vue twig
   *  
   * @param string $view Le nom de la vue
   * @param array $parameters Le tableau des paramètres à passer à la Vue
   * @return string
   */
  public function render($view, array $parameters = []) {
     
      return $this->twig->render($view, $parameters);
  }
 
  /**
   * Redirige vers une autre page en lui passant le nom de la route
   *
   * @param string $routeName
   * @param array $parameters
   * @return Response
   */
  public function redirectRoute($routeName, array $parameters = []) {
     
      return $this->app->redirect(
              $this->app['url_generator']->generate($routeName, $parameters)
      );
  }
  
  /*
   * @param string $message
   * @param string $type
   */
  public function addFlashMessage($message, $type = 'success'){
      
      $this->session->getFlashBag()->add($type, $message);
  }
  
  
  /*
   * 
   * @param string $value
   */
 protected function sanitizeValue(&$value){
      $value = trim(strip_tags($value));
  }
  
/*
 * 
 * @param array $array
 */
  protected function sanitizeArray($array){
      array_walk($array, [$this, 'sanitizeValue']);
  }
  
  
 protected function sanitizePost(){
     $this->sanitizeArray($_POST);
  }
  
}