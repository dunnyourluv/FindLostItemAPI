<?php

namespace DUVX\Middlewares;

use DunnServer\Middlewares\Filter;
use DUVX\Exceptions\AuthException;
use DUVX\Models\AuthModel;
use DUVX\Models\UserModel;

class SetUserExists implements Filter
{
  function doFilter($req, $res, $chain)
  {

    $authorization = $req->getHeaders('Authorization');
    if ($authorization) {
      $basic = explode(' ', $authorization)[1] ?? null;
      if ($basic) {
        $basic = base64_decode($basic);
        $basic = explode(':', $basic);
        $username = $basic[0] ?? null;
        $password = $basic[1] ?? null;
        $userAuth = new AuthModel(UserModel::builder()->username($username)->password($password)->build());
        try {
          $userVerified = $userAuth->login();
          $req->setParams('user', $userVerified);
          $chain->doFilter($req, $res);
        } catch (AuthException $e) {
        }
      }
    }
    $chain->doFilter($req, $res);
  }
}
