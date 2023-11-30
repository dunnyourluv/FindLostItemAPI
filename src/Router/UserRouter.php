<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\ModifiedUserCtl;
use DUVX\Controllers\UserAdminCtl;
use DUVX\Middlewares\AuthFilter;
use DUVX\Middlewares\CustomUploadFilter;
use DUVX\Middlewares\IsAdminFilter;

class UserRouter extends Router
{
  function __construct()
  {
    parent::__construct('/users');
    $this->addRoute('', new UserAdminCtl());
    $this->addRoute('/{uuid}', new ModifiedUserCtl());

    //Filters
    $this->addFilter('/*', new AuthFilter(), new IsAdminFilter());
    $this->addFilter('', new CustomUploadFilter('/uploads/avatar'));
  }
}
