<?php
namespace App\Controllers;
use Core\View;
use App\Models\User;
use App\Models\Category;
use App\Models\Note;
use Core\Validation;

class Categories extends \Core\Controller
{
  public function __call($name, $args)
  {
    $method = $name . 'Action';
    if (method_exists($this, $method)) {
      if ($this->before($name) !== false) {
        call_user_func_array([$this, $method], $args);
        $this->after();
      } else {
        header('Location: /home?error=Must log in');
      }
    } else {
      echo "Method $method not found in controller " . get_class($this);
    }
  }

  public function indexAction()
  {
    $user = User::isAuthenticated();
    $categories = Category::readAll();
    $args = ['user' => $user,
      'categories' => $categories
    ];
    View::renderTemplate('Category/show_categories.html', $args);
  }

  public function readAction()
  {
    $user = User::isAuthenticated();
    $title = Validation::validateData($_GET['title']);
    $cat_id = Validation::validateData($_GET['id']);
    $results = Note::readAll($cat_id);
    $args = ['user' => $user,
      'title' => $title,
      'cat_id' => $cat_id,
      'notes' => $results
    ];
    View::renderTemplate('Category/read_category.html', $args);
  }

  public function addAction()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user_id = $_SESSION['user_id'];
      $title = Validation::validateData($_POST['title']);
      $args = [
        'user_id' => $user_id,
        'title' => $title
      ];
      $result = Category::add($args);
      if ($result) {
        header('Location: /categories/index?success=Added category');
      }
    } else {
      // show add page
      $user = User::isAuthenticated();
      $args = ['user' => $user];
      View::renderTemplate('Category/add_category.html', $args);
    }
  }

  public function editAction()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $title = $_POST['title'];
      $args = array(
        'id' => $id,
        'title' => $title
      );
      $result = Category::update($args);
      if ($result) {
        View::redirect('/categories/index?success=Edited category');
      } else {
        View::redirect('/categories/index?error=Error editing category');
      }
    } else {
      $id = $_GET['id'];
      $user = User::isAuthenticated();
      $category = Category::read($id);
      if ($category) {
        $args = ['user' => $user,
          'category' => $category
        ];
        View::renderTemplate('Category/edit_category.html', $args);
      } else {
        View::redirect('/categories/index?error editing');
      }
    }
  }

  public function deleteAction()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $result = Category::delete($id);
      if ($result) {
        View::redirect('/categories/index?success=Deleted category');
      } else {
        View::redirect('/categories/index?error=Error deleting category');
      }
    }
  }

  protected function before($name)
  {
    // check for authentication
    // filter action
    if ($name === 'index' || $name === 'readCategories') {
      return true;
    } else {
      if (!User::isAuthenticated()) {
        return false;
      }
    }
  }
  protected function after() {}
}
