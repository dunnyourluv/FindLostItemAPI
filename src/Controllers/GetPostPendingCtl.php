<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class GetPostPendingCtl extends Controller
{
  function doGet($req, $res)
  {
    $page = $req->getQuery('page') ?? 1;
    $size = $req->getQuery('size') ?? 10;
    $model = PostModel::builder()->status('pending')->build();
    $posts = $model->getSWithStatus($size, ($page - 1) * $size);
    $payload = HttpPayload::success($posts, 'Get posts successfully!');
    $res->send($payload);
  }
}
