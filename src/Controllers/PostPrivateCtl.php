<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Exceptions\PostException;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class PostPrivateCtl extends Controller
{
  function doGet($req, $res)
  {
    $page = $req->getQuery('page') ?? 1;
    $size = $req->getQuery('size') ?? 10;
    /**
     * @var \DUVX\Models\UserModel | null
     */
    $user = $req->getParams('user');

    $model = PostModel::builder()->userUuid($user->getUuid())->build();
    $posts = $model->getsFromUser($size, ($page - 1) * $size);
    $payload = HttpPayload::success($posts, 'Get posts successfully!');
    $res->send($payload);
  }
}
