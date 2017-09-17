<?php
// load all 3rd party packages installed by Composer
require('../vendor/autoload.php');
// set up Twig template engine
$loader = new Twig_Loader_Filesystem('../App/Views');
$twig = new Twig_Environment($loader, array(
  'cache' => '../App/Views/Cache'
));

session_start();
require('../Core/Router.php');
// autoload classes
spl_autoload_register(function($class) {
  $root = dirname(__DIR__);
  $file = $root . '/' . str_replace('\\','/', $class) . '.php';
  if (is_readable($file)) {
    require $file;
  }
});
// set up automatic routing based controller name and action
$router = new Core\Router;
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('home', ['controller' => 'Home', 'action' => 'index']);
$router->add('admin/{controller}/{action}/{id:\d+}');
$router->add('{controller}/{action}');
$url = $_SERVER['QUERY_STRING'];
$router->dispatch($url);
 ?>
