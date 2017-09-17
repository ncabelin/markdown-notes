<?php
namespace Core;

class Router
{

  // routing table
  protected $routes = [];
  protected $params = [];
  public function add($route, $params = [])
  {
    // convert route to reg exp: escape forward slashes
    $route = preg_replace('/\//','\\/', $route);
    // convert variables in a { }
    $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
    // convert variables with digits in e.g. {id:\d+}
    $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
    // open and close regex
    $route = '/^' . $route . '$/i';
    $this->routes[$route] = $params;
  }

  public function get_routes()
  {
    return $this->routes;
  }

  public function get_params()
  {
    return $this->params;
  }

  public function match($url)
  {
    foreach($this->routes as $route => $params) {
      if (preg_match($route, $url, $matches)) {
        foreach($matches as $key => $match) {
          if (is_string($key)) {
            $params[$key] = $match;
          }
        }

        $this->params = $params;
        return true;
      }
    }
    return false;
  }

  public function dispatch($url)
  {
    $url = $this->removeQueryStringVariables($url);

    if ($this->match($url)) {
      $controller = $this->params['controller'];
      $controller = $this->convertToStudlyCaps($controller);
      $controller = "App\Controllers\\$controller";

      if (class_exists($controller)) {
        $controller_object = new $controller($this->params);

        $action = $this->params['action'];
        $action = $this->convertToCamelCase($action);

        if (preg_match('/action$/i', $action) == 0) {
          $controller_object->$action();
        } else {
          echo "Method $action in controller $controller not found";
        }
      } else {
        echo "Controller class $controller not found";
      }
    } else {
      echo "No route matched.";
    }
  }

  public function convertToStudlyCaps($string)
  {
    return str_replace(' ', '', ucwords(str_replace('-',' ', $string)));
  }

  public function convertToCamelCase($string)
  {
    return lcfirst($this->convertToStudlyCaps($string));
  }

  protected function removeQueryStringVariables($url)
  {
    if ($url != '') {
      $parts = explode('&', $url, 2);

      if (strpos($parts[0], '=') === false) {
        $url = $parts[0];
      } else {
        $url = '';
      }
    }

    return $url;
  }

}
