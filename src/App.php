<?php

namespace DUVX;

use DunnServer\DunnServer;
use DunnServer\Router\Router;
use DUVX\Controllers\HomeCtl;
use DUVX\Middlewares\CORSFilter;
use DUVX\Router\AuthRouter;
use DUVX\Router\ProfileRouter;

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

    // Router setup
    $server->useRouter($apiRouter);

    $server->run();
  }
}
