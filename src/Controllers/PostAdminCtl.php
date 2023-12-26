<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class PostAdminCtl extends Controller
{
  function doGet($req, $res)
  {
    $page = $req->getQuery('page') ?? 1;
    $size = $req->getQuery('size') ?? 10;
    $model = PostModel::builder()->build();
    $posts = $model->getAll($size, ($page - 1) * $size);
    $payload = HttpPayload::success($posts, 'Get posts successfully!');
    $res->send($payload);
  }
}
