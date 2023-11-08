<?php

namespace DUVX;

use DunnWebServer\Main\Server;
use DUVX\Controllers\HomeController;

class App
{
  static function main()
  {
    // if you use cmd to run this project, you can use this command: ./start.bat
    // if you use git bash to run this project, you can use this command: ./start.sh
    $server = new Server();

    $server->addRoute('/', new HomeController());

    $server->run();
  }
}
