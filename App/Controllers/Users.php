<?php
namespace App\Controllers;
use App\Models\User;
use Core\Validation;

class Users extends \Core\Controller
{
  public function __call($name, $args) {
    $method = $name . 'Action';
    if (method_exists($this, $method)) {
      if ($this->before() !== false) {
        call_user_func_array([$this, $method], $args);
        $this->after();
      }
    } else {
      echo "Method $method not found in controller " . get_class($this);
    }
  }

  public function loginAction() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = Validation::validateData($_POST['username']);
      $password = Validation::validateData($_POST['password']);
      $status = User::login($username,$password);
      if ($status === 'logged') {
        header('Location: /categories/index');
      }
    } else {
      header('Location: /');
    }
  }

  public function logoutAction() {
    $_SESSION = array();
    session_destroy();
    header('Location: /');
  }

  public function registerAction() {
    $args = array();
    $args['username'] = Validation::validateData($_POST['username']);
    $args['password'] = Validation::validateData($_POST['password']);
    $args['re_password'] = Validation::validateData($_POST['re_password']);
    $args['email'] = Validation::validateData($_POST['email']);

    $error_msg = array();
    if ($args['password'] !== $args['re_password']) {
      $error_msg[] = 'Passwords don\'t match';
    }
    if (strlen($args['password']) < 8) {
      $error_msg[] = 'Password must be more than 8 characters';
    }
    foreach ($args as $key => $value) {
      if (!$value) {
         $error_msg[] = "$key cannot be empty";
      }
    }
    if (!$error_msg) {
      $status = User::register($args);
      if ($status === 'registered') {
        header('Location: /categories/index');
      }
    } else {
      echo json_encode($error_msg);
    }
  }

  protected function before() {
    // check for authentication
    // return false if you want the code not to be executed
  }
  protected function after() {
  }
}
