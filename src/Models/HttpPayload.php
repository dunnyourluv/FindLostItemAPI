<?php

namespace DUVX\Models;

/**
 * @author LT.Dung
 */
class HttpPayload implements \JsonSerializable
{
  private $data;
  private \Exception | null $error;
  private $code;
  private $message;

  function __construct($data = null, \Exception $err = null, $code = null, $message = null)
  {
    $this->data = $data;
    $this->error = $err;
    $this->code = $code;
    $this->message = $message;
  }

  static function success($data, $message = '', $code = 200)
  {
    return new self($data, null, $code, $message);
  }

  static function failed(\Exception $err, $message = '', $code = 500, $data = null)
  {
    return new self($data, $err, $code, $message);
  }

  function jsonSerialize(): mixed
  {
    $err = null;
    if ($this->error instanceof \Exception) {
      $err = $this->error->getMessage();
    }

    return [
      'code' => $this->code,
      'message' => $this->message,
      'data' => $this->data,
      'error' => $err
    ];
  }

  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * @return mixed
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @return \Exception|null
   */
  public function getError(): \Exception | null
  {
    return $this->error;
  }

  /**
   * @return mixed
   */
  public function getMessage()
  {
    return $this->message;
  }
}
