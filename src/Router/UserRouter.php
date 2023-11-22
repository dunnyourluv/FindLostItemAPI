<?php

namespace DUVX\Router;
use DunnServer\Router\Router;

class UserRouter extends Router
{
  function __construct()
  {
    parent::__construct('/user');
  }
}
