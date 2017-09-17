<?php
namespace Core;
use Core\Config;
use PDO;

abstract class Model
{
  protected static function getDB()
  {
    static $db = null;
    if ($db === null) {
      $host = Config::DB_HOST;
      $dbname = Config::DB_NAME;
      $username = Config::DB_USERNAME;
      $password = Config::DB_PASSWORD;

      try {
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username,$password);
        return $db;
      } catch(PDOException $e) {
        echo $e->getMessage();
      }
    }
    return $db;
  }
}
