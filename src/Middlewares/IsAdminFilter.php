<?php

namespace DUVX\Middlewares;
use DunnServer\Middlewares\Filter;
use DUVX\Exceptions\AuthException;
use DUVX\Models\HttpPayload;

class IsAdminFilter implements Filter
{
  function doFilter($req, $res, $chain)
  {
    /**
     * @var \DUVX\Models\UserModel
     */
    $user = $req->getParams('user');
    if($user && $user->getIsAdmin()) {
      return $chain->doFilter($req, $res);
    }
    $payload = HttpPayload::failed(new AuthException('You are not admin!', 403));
    $res->status($payload->getCode());
    $res->send($payload);
  }
}
