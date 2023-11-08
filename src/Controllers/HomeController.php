<?php

namespace DUVX\Controllers;

use DunnWebServer\MVC\Controller;
use DUVX\Models\IntroModel;
use DUVX\Models\Payload\HttpPayload;

/**
 * @author LT.Dung
 */
class HomeController extends Controller
{
  function doGet(\DunnWebServer\Http\Request $req, \DunnWebServer\Http\Response $res)
  {
    $introModel = new IntroModel();
    $payload = HttpPayload::success($introModel, 'OK'); // Default code is 200
    $res->json($payload); // Response with JSON to client
    // You can run this project and open http://localhost:8080/ to see the result
  }
}
