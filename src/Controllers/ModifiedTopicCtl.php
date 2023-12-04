<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Exceptions\TopicException;
use DUVX\Models\HttpPayload;
use DUVX\Models\TopicModel;

class ModifiedTopicCtl extends Controller
{
  function doPut($req, $res)
  {
    $uuid = $req->getParams('uuid');
    $name = $req->getBody('name');
    $topic = new TopicModel($uuid, $name);
    try {
      $payload = HttpPayload::success($topic->update(), 'Update topic successfully!');
    } catch (TopicException $e) {
      $payload = HttpPayload::failed($e);
    }

    $res->status($payload->getCode())->send($payload);
  }

  function doDelete($req, $res)
  {
    $uuid = $req->getParams('uuid');
    $topic = new TopicModel($uuid);
    try {
      $payload = HttpPayload::success($topic->delete(), 'Delete topic successfully!');
    } catch (TopicException $e) {
      $payload = HttpPayload::failed($e);
    }

    $res->status($payload->getCode())->send($payload);
  }
}
