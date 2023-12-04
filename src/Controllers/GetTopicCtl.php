<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\TopicModel;

class GetTopicCtl extends Controller
{
  function doGet($req, $res)
  {
    $page = $req->getQuery('page') ?? 1;
    $size = $req->getQuery('size') ?? 10;

    $model = new TopicModel();
    $topics = $model->getAll($size, ($page - 1) * $size);
    $payload = HttpPayload::success($topics, 'Get topics successfully!');
    $res->send($payload);
  }
}
