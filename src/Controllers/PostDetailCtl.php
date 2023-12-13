<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class PostDetailCtl extends Controller
{
  function doGet($req, $res)
  {
    $uuid = $req->getParams('uuid');
    $post = PostModel::builder()->uuid($uuid)->build()->getById();
    $payload = HttpPayload::success($post);
    $res->json($payload);
  }
}
