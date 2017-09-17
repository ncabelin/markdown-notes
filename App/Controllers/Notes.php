<?php
namespace App\Controllers;
use Core\View;
use App\Models\User;
use App\Models\Note;
use App\Models\Category;
use Core\Validation;

class Notes extends \Core\Controller
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

  public function readAction()
  {
    $user = User::isAuthenticated();
    $cat_title = Validation::validateData($_GET['cat_title']);
    $id = Validation::validateData($_GET['id']);
    $result = Note::read($id);
    if (!$cat_title) {
      $cat_id = $result['cat_id'];
      $cat_result = Category::read($cat_id);
      $cat_title = $cat_result['title'];
    }
    if ($result) {
      $args = ['user' => $user,
        'cat_title' => $cat_title,
        'cat_id' => $result['cat_id'],
        'author_id' => $result['user_id'],
        'id' => $id,
        'title' => $result['title'],
        'content' => $result['content'],
        'share' => $result['share']
      ];
      View::renderTemplate('Note/read_note.html', $args);
    }
  }

  public function allAction()
  {
    $user = User::isAuthenticated();
    $results = Note::all();
    $args = [
      'user' => $user,
      'notes' => $results
    ];
    View::renderTemplate('Note/all_notes.html', $args);
  }

  public function addAction()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = User::isAuthenticated();
      $cat_id = Validation::validateData($_POST['cat_id']);
      $cat_title = Validation::validateData($_POST['cat_title']);
      $title = Validation::validateData($_POST['title']);
      $content = $_POST['content'];
      $share = Validation::validateData($_POST['share']);
      $args = [
        'user_id' => $user['id'],
        'cat_id' => $cat_id,
        'title' => $title,
        'content' => $content,
        'share' =>$share
      ];
      $result = Note::add($args);
      if ($result) {
        header("Location: /categories/read?id=$cat_id&title=$cat_title&success=Added category");
      }
    } else {
      // show add page
      $user = User::isAuthenticated();
      $cat_id = Validation::validateData($_GET['id']);
      $cat_title = Validation::validateData($_GET['title']);
      $args = ['user' => $user,
        'cat_id' => $cat_id,
        'cat_title' => $cat_title
      ];
      View::renderTemplate('Note/add_note.html', $args);
    }
  }

  public function editAction()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = Validation::validateData($_POST['id']);
      $cat_id = Validation::validateData($_POST['cat_id']);
      $cat_title = Validation::validateData($_POST['cat_title']);
      $title = Validation::validateData($_POST['title']);
      $content = $_POST['content'];
      $share = Validation::validateData($_POST['share']);
      $args = array(
        'id' => $id,
        'cat_id' => $cat_id,
        'title' => $title,
        'content' => $content,
        'share' => $share
      );
      $result = Note::update($args);
      if ($result) {
        View::redirect("/notes/read?cat_title=$cat_title&id=$id&success=Edited category");
      } else {
        View::redirect("/categories/read?title=$cat_title&id=$cat_id&error=Error editing category");
      }
    } else {
      // show edit page
      $user = User::isAuthenticated();
      $id = Validation::validateData($_GET['id']);
      $cat_title = Validation::validateData($_GET['cat_title']);
      $result = Note::read($id);
      if ($result) {
        $args = ['user' => $user,
          'id' => $result['id'],
          'cat_id' => $result['cat_id'],
          'cat_title' => $cat_title,
          'title' => $result['title'],
          'content' => $result['content'],
          'share' => $result['share']
        ];
        View::renderTemplate('Note/edit_note.html', $args);
      }
    }
  }

  public function deleteAction()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $cat_id = $_POST['cat_id'];
      $cat_title = $_POST['cat_title'];
      $result = Note::delete($id);
      if ($result) {
        View::redirect("/categories/read?id=$cat_id&title=$cat_title&success=Deleted category");
      } else {
        View::redirect("/categories/read?id=$cat_id&title=$cat_title&error=Error deleting category");
      }
    }
  }

  protected function before($name)
  {
    // check for authentication
    // filter action
    if ($name === 'read') {
      return true;
    } else {
      if (!User::isAuthenticated()) {
        return false;
      }
    }
  }

  protected function after() {
  }
}
