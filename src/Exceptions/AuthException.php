<?php

namespace DUVX\Exceptions;

/**
 * @author LT.Dung
 */
class AuthException extends DUVXException
{
  function __construct($message, $code)
  {
    parent::__construct($message, $code);
  }

  static function unableToVerify($message = 'Wrong username or password!', $code = 401)
  {
    return new static($message, $code);
  }

  static function unauthorized($message = "Unauthorized", $code = 401)
  {
    return new static($message, $code);
  }
}
