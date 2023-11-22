<?php

namespace DUVX\Router;

use DunnServer\Middlewares\HandleFileFilter;
use DunnServer\Router\Router;
use DUVX\Controllers\LoginCtl;
use DUVX\Controllers\RegisterCtl;
use DUVX\Middlewares\CustomUploadFilter;

class AuthRouter extends Router
{
  function __construct()
  {
    parent::__construct('/auth');
    $this->addRoute('/login', new LoginCtl());
    $this->addRoute('/register', new RegisterCtl());

    //File Upload Filter
    $this->addFilter('/register', new CustomUploadFilter('/uploads/avatar'));
  }
}
