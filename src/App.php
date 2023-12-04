<?php

namespace DUVX;

use DunnServer\DunnServer;
use DunnServer\Router\Router;
use DUVX\Controllers\HomeCtl;
use DUVX\Controllers\NotFoundCtl;
use DUVX\Middlewares\CORSFilter;
use DUVX\Router\AuthRouter;
use DUVX\Router\ProfileRouter;
use DUVX\Router\TopicRouter;
use DUVX\Router\UserRouter;

class App
{
  static function main()
  {
    // Setup environment variables
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $server = new DunnServer();

    // CORS Filter
    $server->addFilter('/*', new CORSFilter());


    $server->addRoute('/', new HomeCtl());

    $apiRouter = new Router('/api/v1');
    $apiRouter->useRouter(new AuthRouter());
    $apiRouter->useRouter(new ProfileRouter());
    $apiRouter->useRouter(new UserRouter());
    $apiRouter->useRouter(new TopicRouter());
    // Router setup
    $server->useRouter($apiRouter);


    // NotFound
    $server->addRoute('/*', new NotFoundCtl());

    $server->run();
  }
}
