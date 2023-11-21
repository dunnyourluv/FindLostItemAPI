<?php

namespace DUVX\Controllers;
use DunnServer\MVC\Controller;
use DUVX\Exceptions\AuthException;
use DUVX\Models\AuthModel;
use DUVX\Models\HttpPayload;
use DUVX\Models\UserModel;

class LoginCtl extends Controller
{
  function doPost($req, $res)
  {
    $username = $req->getBody('username');
    $password = $req->getBody('password');
    $userBuilder = UserModel::builder();
    $userBuilder->username($username)->password($password);
    $auth = new AuthModel($userBuilder->build());
    try {
      $user = $auth->login();
      $res->json(HttpPayload::success($user, 'Login success!'));
    } catch (AuthException $th) {
      $res->status($th->getCode())->json(HttpPayload::failed($th));
    }
  }
}
