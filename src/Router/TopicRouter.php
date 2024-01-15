<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\GetTopicCtl;
use DUVX\Controllers\ModifiedTopicCtl;
use DUVX\Controllers\PostTopicCtl;
use DUVX\Middlewares\AuthFilter;
use DUVX\Middlewares\IsAdminFilter;

class TopicRouter extends Router
{
  function __construct()
  {
    parent::__construct('/topic'); // http://localhost:8080/topic/add
    $this->addRoute('/add', new PostTopicCtl());
    $this->addRoute('', new GetTopicCtl());
    $this->addRoute('/{uuid}', new ModifiedTopicCtl());

    // Filter
    $this->addFilter('/add', new AuthFilter(), new IsAdminFilter());
    $this->addFilter('/{uuid}', new AuthFilter(), new IsAdminFilter());
  }
}
