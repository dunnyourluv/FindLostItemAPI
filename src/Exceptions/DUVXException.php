<?php

namespace DUVX\Exceptions;

/**
 * @author LT.Dung
 */
class DUVXException extends \Exception implements \JsonSerializable
{
  function jsonSerialize(): mixed
  {
    return [
      "message" => $this->message,
      "code" => $this->code
    ];
  }
}
