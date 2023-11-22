<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\AuthException;
use DUVX\Models\AuthModel;
use DUVX\Models\HttpPayload;
use DUVX\Models\UserModel;

class UserAdminCtl extends Controller
{
  function doGet($req, $res)
  {
    $page = $req->getQuery('page') ?? 1;
    $size = $req->getQuery('size') ?? 10;
    $model = UserModel::builder()->build();
    $users = $model->getAll($size, ($page - 1) * $size);
    $payload = HttpPayload::success($users, 'Get users successfully!');
    $res->send($payload);
  }

  function doPost($req, $res)
  {
    $body = $req->getBody();
    $avatar = $req->getUploads()->get('avatar', new DunnArray())->get(0);
    $model = UserModel::builder()->fromBody($body)->uuid(uniqid())->avatar($avatar ? $avatar->getPath() : null)->build();
    try {
      $userAuth = new AuthModel($model);
      $user = $userAuth->register();
      $payload = HttpPayload::success($user, 'Add user successfully!');
    } catch (AuthException $e) {
      $payload = HttpPayload::failed($e);
      $res->status($payload->getCode());
      if ($avatar) {
        $root = $req->server('DOCUMENT_ROOT');
        $avatar->setPath($root . $avatar->getPath());
        $avatar->remove();
      }
    }

    $res->send($payload);
  }
}
