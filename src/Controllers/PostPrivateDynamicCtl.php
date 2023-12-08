<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Exceptions\PostException;
use DUVX\Models\HttpPayload;
use DUVX\Models\PostModel;

class PostPrivateDynamicCtl extends Controller
{
  function doPut($req, $res)
  {
    /**
     * @var \DUVX\Models\UserModel | null
     */
    $user = $req->getParams('user');
    $body = $req->getBody();
    $uuid = $req->getParams('uuid');
    $post = PostModel::builder()->fromBody($body)->uuid($uuid)->userUuid($user->getUuid())->build();
    try {
      $post = $post->update();
      $payload = HttpPayload::success($post, 'Post updated successfully!');
    } catch (PostException $e) {
      $payload = HttpPayload::failed($e);
    }
    $res->status($payload->getCode())->send($payload);
  }

  function doDelete($req, $res)
  {
    $uuid = $req->getParams('uuid');
    /**
     * @var \DUVX\Models\UserModel
     */
    $user = $req->getParams('user');

    $post = PostModel::builder()->uuid($uuid)->userUuid($user->getUuid())->build();
    try {
      $data = $post->getById();
      $post->delete();
      $payload = HttpPayload::success($data, 'Post deleted successfully!');
    } catch (PostException $e) {
      $payload = HttpPayload::failed($e);
    }
    $res->status($payload->getCode())->send($payload);
  }
}
