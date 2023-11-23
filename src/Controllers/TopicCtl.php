<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DUVX\Models\HttpPayload;
use DUVX\Models\TopicModel;

class TopicCtl extends Controller
{
    function doGet($req, $res)
    {
        $model = TopicModel::builder()->build();
        $topics = $model->getAll();
        $payload = HttpPayload::success($topics, 'List of topics');
        $res->send($payload);
    }
    function doPost($req, $res)
    {
        $body = $req->getBody();
        $model = TopicModel::builder()->fromBody($body)->build();
        $model->save();
        $payload = HttpPayload::success($model, 'Create topic successfully!');
        $res->send($payload);
    }
    function doPut($req, $res)
    {
        $body = $req->getBody();
        $exceptions = ['uuid'];
        $payload = HttpPayload::success($body, 'Update topic successfully!');
        $res->send($payload);
    }
    function doDelete($req, $res)
    {
        $body = $req->getBody();
        $payload = HttpPayload::success($body, 'Delete topic successfully!');
        $res->send($payload);
    }
}
