<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\AuthException;
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
      $errors = new DunnArray(...$user->validate());
      if ($errors->length() > 0) {
        $mess = $errors->join(', ');
        throw new AuthException($mess, 400);
      } else {
        if ($user->has())
          throw new AuthException('Username or email already exists!', 500);
        $user = $user->save();
        $payload = HttpPayload::success($user, 'Register successfully!');
      }
    } catch (AuthException $th) {
      $payload = HttpPayload::failed($th);
      $res->status($payload->getCode());
      if ($avatar)
        $avatar->remove();
    }
    $res->send($payload);
  }
}
