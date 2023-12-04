<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Exceptions\TopicException;
use DUVX\Models\HttpPayload;
use DUVX\Models\TopicModel;

class PostTopicCtl extends Controller
{
  function doPost($req, $res)
  {
    $name = $req->getBody('name');
    $topic = new TopicModel(uniqid(), $name);
    try {
      $topic = $topic->save();
      $payload = HttpPayload::success($topic);
    } catch (TopicException $e) {
      $payload = HttpPayload::failed($e);
    }

    $res->status($payload->getCode())->send($payload);
  }
}
