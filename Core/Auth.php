<?php
namespace Core;
class Auth
{

  private static $_instance;

  private function __construct() {} // disallow creating a new object of the class

  private function __clone() {} // disallow cloning class

  public static function init()
  {
    session_start();
  }

  public static function getInstance()
  {
    if (static::$_instance === NULL) {
      static::$_instance = new Auth();
    }

    return static::$_instance;
  }
}
