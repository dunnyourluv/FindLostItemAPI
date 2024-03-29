<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\PostDetailCtl;
use DUVX\Controllers\RecommendPostCtl;
use DUVX\Controllers\SearchPostCtl;
use DUVX\Middlewares\SetUserExists;

class PostPublicRouter extends Router
{
  function __construct()
  {
    parent::__construct('/posts');
    $this->addRoute('/recommend', new RecommendPostCtl());
    $this->addRoute('/search', new SearchPostCtl());
    $this->addRoute('/{uuid}', new PostDetailCtl());
    // A -> C
    // A -> Filter -> C
    //Filter
    // localhost:8080/recommend

    $this->addFilter('/{uuid}', new SetUserExists());
  }
}
