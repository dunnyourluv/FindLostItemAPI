<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\PostPrivateAddCtl;
use DUVX\Controllers\PostPrivateCtl;
use DUVX\Middlewares\AuthFilter;
use DUVX\Middlewares\CustomUploadFilter;

class PostPrivateRouter extends Router
{
  function __construct()
  {
    parent::__construct('/me/posts');
    $this->addRoute('', new PostPrivateCtl());
    $this->addRoute('/add', new PostPrivateAddCtl());

    //Filter
    $this->addFilter('/*', new AuthFilter());
    $this->addFilter('/add', new CustomUploadFilter('/uploads/post_images'));
  }
}
