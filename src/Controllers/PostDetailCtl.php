<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class PostDetailCtl extends Controller
{
  function doGet($req, $res)
  {

    /**
     * @var \DUVX\Models\UserModel $user
     */
    $user = $req->getParams('user');

    $uuid = $req->getParams('uuid');
    $post = PostModel::builder()->uuid($uuid)->build()->getById();

    if (!$post) {
      $payload = HttpPayload::success(null, 'Post not found');
      return $res->json($payload);
    }

    if ($user && $user->getIsAdmin()) {
      $payload = HttpPayload::success($post, 'Admin can access this post');
      return $res->json($payload);

    }

    if ($user && $post->getUserUuid() == $user->getUuid()) {
      $payload = HttpPayload::success($post);
    } else if ($post->isPublic()) {
      $payload = HttpPayload::success($post);
    } else {
      $payload = HttpPayload::success(null, 'Cannot access this post');
    }

    $res->json($payload);
  }
}
