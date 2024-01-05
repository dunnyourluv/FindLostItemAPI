<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class CountPostPendingCtl extends Controller
{
  function doGet($req, $res)
  {
    $model = PostModel::builder()->status('pending')->build();
    $count = $model->countWithStatus();
    $payload = HttpPayload::success(['count' => $count], 'Get count successfully!');
    $res->send($payload);
  }
}
