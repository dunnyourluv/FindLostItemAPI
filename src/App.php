<?php

namespace DUVX;

use DunnServer\DunnServer;
use DunnServer\Router\Router;
use DUVX\Controllers\HomeCtl;
use DUVX\Router\AuthRouter;

class App
{
  static function main()
  {
    // Setup environment variables
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $server = new DunnServer();

    $server->addRoute('/', new HomeCtl());

    $apiRouter = new Router('/api/v1');
    $apiRouter->useRouter(new AuthRouter());

    // Router setup
    $server->useRouter($apiRouter);


    $server->run();
  }
}
