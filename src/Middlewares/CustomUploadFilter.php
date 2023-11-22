<?php

namespace DUVX\Middlewares;

use DunnServer\Middlewares\HandleFileFilter;
use DunnServer\Utils\DunnFile;
use DUVX\Exceptions\UploadException;
use DUVX\Models\HttpPayload;

class CustomUploadFilter extends HandleFileFilter
{
  function __construct($dir)
  {
    parent::__construct($_SERVER['DOCUMENT_ROOT'] . $dir, $dir);
  }

  function doFilter($req, $res, $chain)
  {
    $upload = $req->getUploads();
    $isImage = true;
    $upload->values()->forEach(function ($files) use (&$isImage) {
      $files->forEach(function (DunnFile $file) use (&$isImage) {
        if ($file->getType() !== 'image/png' && $file->getType() !== 'image/jpeg') {
          $isImage = false;
          return;
        }
      });
    });
    if (!$isImage) {
      $payload = HttpPayload::failed(new UploadException('Files must be an image', 400));
      $res->status($payload->getCode())->send($payload);
      return;
    }

    return parent::doFilter($req, $res, $chain);
  }
}
