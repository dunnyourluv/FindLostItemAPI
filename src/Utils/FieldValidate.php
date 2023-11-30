<?php

namespace DUVX\Utils;

class FieldValidate
{
  static function validateEmail($email)
  {
    $pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
    return preg_match($pattern, $email);
  }

  static function validatePassword($password)
  {
    return strlen($password) >= 8;
  }

  static function validateUsername($username)
  {
    return strlen($username) >= 6;
  }
}
