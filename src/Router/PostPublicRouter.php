<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\PostDetailCtl;
use DUVX\Controllers\RecommendPostCtl;
use DUVX\Controllers\SearchPostCtl;

class PostPublicRouter extends Router
{
  function __construct()
  {
    parent::__construct('/posts');
    $this->addRoute('/recommend', new RecommendPostCtl());
    $this->addRoute('/search', new SearchPostCtl());
    $this->addRoute('/{uuid}', new PostDetailCtl());
  }
}
