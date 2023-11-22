<?php

namespace DUVX\Middlewares;

use DunnServer\Middlewares\Filter;

class CORSFilter implements Filter
{
  function doFilter($req, $res, $chain)
  {
    $res->setHeader('Access-Control-Allow-Origin', '*');
    $res->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $res->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    $res->setHeader('Access-Control-Allow-Credentials', 'true');
    if ($req->getMethod() == 'OPTIONS') {
      $res->status(200)->send('');
      return;
    }
    $chain->doFilter($req, $res);
  }
}
