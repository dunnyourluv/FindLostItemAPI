<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DunnServer\Utils\DunnArray;
use DunnServer\Utils\DunnFile;
use DUVX\Exceptions\ImageException;
use DUVX\Exceptions\PostException;
use DUVX\Models\HttpPayload;
use DUVX\Models\ImageModel;
use DUVX\Models\PostModel;

class PostPrivateAddCtl extends Controller
{
  function doPost($req, $res)
  {
    /**
     * @var \DUVX\Models\UserModel | null
     */
    $user = $req->getParams('user');
    $newPost = PostModel::builder()->fromBody($req->getBody())->uuid(uniqid())->userUuid($user->getUuid())->status('pending')->build();

    try {
      $newPost = $newPost->save();
      /**
       * @var DunnArray<\DUVX\Models\ImageModel>
       */
      $imageModels = new DunnArray();

      $images = $req->getUploads()->get('images', new DunnArray());
      foreach ($images->toArray() as $img) {
        $model = new ImageModel(uniqid(), $newPost->getUuid(), $img->getPath());
        $imageModels->push($model);
      }

      $imageModels->forEach(function (ImageModel $model) {
        $model->save();
      });
      $payload = HttpPayload::success($newPost, 'Post created successfully!');
    } catch (PostException $e) {
      $payload = HttpPayload::failed($e);
    } catch (ImageException $e) {
      $payload = HttpPayload::failed($e);
      $root = $req->getParams('_uploadRoot');
      $images->forEach(function (DunnFile $img) use ($root) {
        $img->setPath($root . $img->getPath());
        $img->remove();
      });
    }

    $res->status($payload->getCode())->send($payload);
  }
}
