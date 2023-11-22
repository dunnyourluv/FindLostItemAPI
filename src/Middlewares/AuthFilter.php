<?php

namespace DUVX\Middlewares;

use DunnServer\Middlewares\Filter;
use DUVX\Exceptions\AuthException;
use DUVX\Models\AuthModel;
use DUVX\Models\HttpPayload;
use DUVX\Models\UserModel;

class AuthFilter implements Filter
{
  function doFilter($req, $res, $chain)
  {
    $authorization = $req->getHeaders('Authorization');
    if($authorization) {
      $basic = explode(' ', $authorization)[1] ?? null;
      if($basic) {
        $basic = base64_decode($basic);
        $basic = explode(':', $basic);
        $username = $basic[0] ?? null;
        $password = $basic[1] ?? null;
        $userAuth = new AuthModel(UserModel::builder()->username($username)->password($password)->build());
        try {
          $userVerified = $userAuth->login();
          $req->setParams('user', $userVerified);
          $chain->doFilter($req, $res);
        }catch(AuthException $e)
        {
          $payload = HttpPayload::failed($e);
          $res->status($payload->getCode())->send($payload);
        }
      }
    }else {
      $payload = HttpPayload::failed(new AuthException('Authorization header not found', 401));
      $res->status($payload->getCode())->send($payload);
    }
  }
}
