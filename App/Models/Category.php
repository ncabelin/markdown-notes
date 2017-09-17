<?php
namespace App\Models;
use App\Models\User;
use Core\Validation;
use PDO;

class Category extends \Core\Model
{

  public static function readAll()
  {
    try {
      $user = User::isAuthenticated();
      $sql = "SELECT * FROM category WHERE user_id = :userId ORDER BY title ASC";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function add($args)
  {
    $title = Validation::validateData($args['title']);
    $user = User::isAuthenticated();
    try {
      $sql = "INSERT INTO category(user_id, title) VALUES (:userId,:title)";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':userId', $args['user_id'], PDO::PARAM_INT);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      if ($stmt->execute()) {
        return true;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function read($id)
  {
    $user = User::isAuthenticated();
    $id = Validation::validateData($id);
    try {
      $sql = "SELECT * FROM category WHERE id = :id AND user_id = :userId";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function update($args)
  {
    $title = Validation::validateData($args['title']);
    $id = Validation::validateData($args['id']);
    $user = User::isAuthenticated();
    try {
      $sql = "UPDATE category SET title = :title WHERE id = :id AND user_id = :userId";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        return true;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function delete($id)
  {
    $id = Validation::validateData($id);
    $user = User::isAuthenticated();
    try {
      $sql = "DELETE FROM category WHERE id = :id AND user_id = :userId";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
      if ($stmt->execute()) {
        return true;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

}
