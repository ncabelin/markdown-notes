<?php
namespace App\Models;
use App\Models\User;
use Core\Validation;
use PDO;

class Note extends \Core\Model
{

  public static function readAll($cat_id)
  {
    try {
      $user = User::isAuthenticated();
      $sql = "SELECT * FROM note WHERE user_id = :userId AND cat_id = :catId ORDER BY date_modified DESC";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
      $stmt->bindParam(':catId', $cat_id, PDO::PARAM_INT);
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
    $title = $args['title'];
    $cat_id = $args['cat_id'];
    $content = $args['content'];
    $share = $args['share'];
    $user = User::isAuthenticated();
    try {
      $sql = "INSERT INTO note(user_id, cat_id, title, content, share) VALUES (:userId, :catId, :title, :content, :share)";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
      $stmt->bindParam(':catId', $cat_id, PDO::PARAM_INT);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':content', $content, PDO::PARAM_STR);
      $stmt->bindParam(':share', $share, PDO::PARAM_STR);
      if ($stmt->execute()) {
        return true;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function read($id)
  {
    try {
      $sql = "SELECT * FROM note WHERE id = :id";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
    $title = $args['title'];
    $content = $args['content'];
    $share = $args['share'];
    $id = $args['id'];
    $user = User::isAuthenticated();
    try {
      $sql = "UPDATE note SET title = :title, content = :content, share = :share WHERE id = :id AND user_id = :userId";
      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':content', $content, PDO::PARAM_STR);
      $stmt->bindParam(':share', $share, PDO::PARAM_STR);
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
    $user = User::isAuthenticated();
    try {
      $sql = "DELETE FROM note WHERE id = :id AND user_id = :userId";
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

  public static function all()
  {
    $user = User::isAuthenticated();
    $user_id = $user['id'];
    $sql = "SELECT * FROM note WHERE user_id = $user_id";
    $db = static::getDB();
    $stmt = $db->query($sql);
    $results = $stmt->fetchAll();
    return $results;
  }

  public static function allPublic($limit)
  {
    $sql = "SELECT * FROM note WHERE share = 'y' ORDER BY date_modified DESC LIMIT $limit";
    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();
    return $results;
  }
}
