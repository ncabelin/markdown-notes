<?php
namespace App\Controllers;
use Core\View;
use App\Models\User;
use App\Models\Note;

class Home extends \Core\Controller
{
  public function index()
  {
    $query = $this->route_params;
    $user = User::isAuthenticated();
    $notes = Note::allPublic(10);
    $args = ['user' => $user,
      'notes' => $notes
    ];
    View::renderTemplate('Home/index.html', $args);
  }
}
