<?php
namespace Core;
class Validation
{
  public static function validateData($formData) {
    $formData = trim(stripslashes(htmlspecialchars($formData)));
    return $formData;
  }
}
