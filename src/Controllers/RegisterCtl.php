<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\AuthException;
use DUVX\Models\AuthModel;
use DUVX\Models\HttpPayload;
use DUVX\Models\UserModel;

class RegisterCtl extends Controller
{
  function doPost($req, $res)
  {
    $body = $req->getBody();
    $uploads = $req->getUploads();
    $avatar = $uploads->get('avatar', new DunnArray())->get(0); // [File]
    $user = UserModel::builder()->fromBody($body)->isAdmin(false)->uuid(uniqid())->avatar($avatar ? $avatar->getPath() : null)->build();
    try {
      $userAuth = new AuthModel($user);
      $user = $userAuth->register();
      $payload = HttpPayload::success($user, 'Register successfully!');
    } catch (AuthException $e) {
      $payload = HttpPayload::failed($e);
      $res->status($payload->getCode());
      if ($avatar) {
        $root = $req->server('DOCUMENT_ROOT');
        $avatar->setPath($root . $avatar->getPath());
        $avatar->remove();
      }
      ;
    }
    $res->send($payload);
  }
}
