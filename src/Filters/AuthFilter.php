<?php

namespace DUVX\Filters;

use DunnWebServer\Filter\DoFilter;
use DunnWebServer\Filter\Filter;
use DunnWebServer\Http\Request;
use DunnWebServer\Http\Response;
use DUVX\Exceptions\AuthException;
use DUVX\Models\Payload\HttpPayload;

/**
 * @author LT.Dung
 */
class AuthFilter implements Filter
{
  function doFilter(Request $req, Response $res, DoFilter $chain)
  {
    // We use basic auth to authenticate user (username:password) (base64 encoded)
    // If you don't know how to use basic auth, please google it
    $auth = $req->getHeaders('Authorization');
    if ($auth) {
      $auth = explode(' ', $auth);
      $data = explode(':', base64_decode($auth[1]));
      $username = $data[0];
      $password = $data[1];

      // Write your own authentication logic here

      //...

      // After authentication, we can continue to next filter or controller
      $chain->doFilter($req, $res);
    } else {
      $res->setHeader('WWW-Authenticate', 'Basic realm="DUVX"');
      $payload = new HttpPayload(AuthException::unauthorized());

      // When you response an error payload, you should set status code to response with $res->status($code)
      $res->status($payload->getCode())->json($payload);
    }
  }
}
