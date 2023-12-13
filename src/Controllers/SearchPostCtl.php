<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class SearchPostCtl extends Controller
{
  function doGet($req, $res)
  {
    $keyword = $req->getQuery('keyword');
    $topic = $req->getQuery('topic');
    $address = $req->getQuery('location');
    $posts = PostModel::search($keyword, $topic, $address);
    $payload = HttpPayload::success($posts, 'Search posts successfully');
    $res->send($payload);
  }
}
