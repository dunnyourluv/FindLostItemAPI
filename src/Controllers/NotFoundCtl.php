<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;

class NotFoundCtl extends Controller
{
  function doGet($req, $res)
  {
    $res->status(404);
    $res->send('Not found!');
  }

  function doPost($req, $res)
  {
    $res->status(404);
    $res->send('Not found!');
  }

  function doPut($req, $res)
  {
    $res->status(404);
    $res->send('Not found!');
  }

  function doDelete($req, $res)
  {
    $res->status(404);
    $res->send('Not found!');
  }
}
