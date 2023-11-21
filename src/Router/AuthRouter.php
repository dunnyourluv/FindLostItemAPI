<?php

namespace DUVX\Router;
use DunnServer\Router\Router;
use DUVX\Controllers\LoginCtl;

class AuthRouter extends Router
{
  function __construct()
  {
    parent::__construct('/auth');
    $this->addRoute('/login', new LoginCtl());
  }
}
