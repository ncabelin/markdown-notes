<?php
namespace App\Models;
use PDO;

class User extends \Core\Model
{

  public static function login($username, $password)
  {
    try {
      $sql = "SELECT * FROM user WHERE username = :userName";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':userName', $username, PDO::PARAM_STR);
      if ($stmt->execute()) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
          $hashed_password = $user['password'];
          if (password_verify($password, $hashed_password)) {
            // password is correct
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            return 'logged';
          } else {
            // password is not correct
            echo 'Username / Password incorrect';
          }
        } else {
          echo 'User Not found';
        }
      } else {
        echo 'Error connecting';
      };
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function register($args)
  {
    $hashed_password = password_hash($args['password'], PASSWORD_DEFAULT);
    try {
      $sql = "INSERT INTO user(username, password, email) VALUES (:userName,:passWord,:email)";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':userName', $args['username'], PDO::PARAM_STR);
      $stmt->bindParam(':passWord', $hashed_password, PDO::PARAM_STR);
      $stmt->bindParam(':email', $args['email'], PDO::PARAM_STR);
      if ($stmt->execute()) {
        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['username'] = $args['username'];
        return 'registered';
      } else {
        return 'cannot register';
      }
    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  public static function isAuthenticated()
  {
    if (isset($_SESSION['username'])) {
      $user = array(
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username']
      );
      return $user;
    } else {
      return array();
    }
  }
}
