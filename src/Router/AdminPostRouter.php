<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\AcceptPostCtl;
use DUVX\Controllers\CountPostPendingCtl;
use DUVX\Controllers\GetPostPendingCtl;
use DUVX\Controllers\PostAdminCtl;
use DUVX\Controllers\RejectPostCtl;
use DUVX\Middlewares\AuthFilter;
use DUVX\Middlewares\IsAdminFilter;

class AdminPostRouter extends Router
{
  function __construct()
  {
    parent::__construct('/admin/posts'); //http://localhost:8080/admin/posts/123/accept

    $this->addRoute('/{uuid}/accept', new AcceptPostCtl());
    $this->addRoute('/{uuid}/reject', new RejectPostCtl());

    $this->addRoute('', new PostAdminCtl());
    $this->addRoute('/pending', new GetPostPendingCtl());
    $this->addRoute('/pending/count', new CountPostPendingCtl());
    $this->addFilter('/*', new AuthFilter(), new IsAdminFilter());
  }
}
