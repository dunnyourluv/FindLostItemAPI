<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Exceptions\UserException;
use DUVX\Models\HttpPayload;
use DUVX\Models\UserModel;

class ModifiedUserCtl extends Controller
{

  function doPut($req, $res)
  {
    $uuid = $req->getParams('uuid');
    if ($uuid) {
      $model = UserModel::builder()->uuid($uuid)->build();
      $user = $model->getById();
      if ($user) {
        $userFromClient = UserModel::builder()->fromBody($req->getBody())->build();
        try {
          $user = $user->updateFromUser($userFromClient);
          $payload = HttpPayload::success($user, 'User Updated');
        } catch (UserException $e) {
          $payload = HttpPayload::failed($e);
        }
      } else {
        $payload = HttpPayload::failed(new UserException('User cannot be found', 404));
      }
    } else {
      $payload = HttpPayload::failed(new UserException('User cannot be found', 404));
    }
    $res->status($payload->getCode())->send($payload);
  }

  function doDelete($req, $res)
  {
    $uuid = $req->getParams('uuid');
    $model = UserModel::builder()->uuid($uuid)->build();
    $user = $model->getById();
    if ($user) {
      $user->delete();
      $payload = HttpPayload::success($user, 'User Deleted');
    } else {
      $payload = HttpPayload::failed(new UserException('User cannot be found', 404));
    }
    $res->status($payload->getCode())->send($payload);
  }
}
