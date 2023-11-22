<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;

class ProfileMeCtl extends Controller
{
  function doGet($req, $res)
  {
    /**
     * @var \DUVX\Models\UserModel
     */
    $user = $req->getParams('user');
    $payload = HttpPayload::success($user, 'Your Info');
    $res->send($payload);
  }
}
