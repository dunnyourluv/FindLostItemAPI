<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Exceptions\PostException;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class RejectPostCtl extends Controller
{
  function doPut($req, $res)
  {
    $id = $req->getParams('uuid');
    $model = PostModel::builder()->uuid($id)->build();
    try {
      $post = $model->reject();
      $payload = HttpPayload::success($post, 'Post accepted');
    } catch (PostException $e) {
      $payload = HttpPayload::failed($e);
    }

    $res->status($payload->getCode())->send($payload);
  }
}
