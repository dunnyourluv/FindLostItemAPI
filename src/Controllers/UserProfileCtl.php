<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\UserModel;

class UserProfileCtl extends Controller
{
  function doGet($req, $res)
  {
    $username = $req->getParams('username');
    $model = UserModel::builder()->build();
    $user = $model->getByUsername($username);
    $payload = HttpPayload::success($user, 'User Info');
    $res->send($payload);
  }
}
