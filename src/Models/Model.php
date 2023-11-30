<?php

namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DunnServer\Utils\DunnMap;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;

/**
 * @template T
 */
abstract class Model implements \JsonSerializable
{

  /**
   * @var string
   */
  protected $tableName;

  function __construct($tableName)
  {
    $this->tableName = $tableName;
  }

  /**
   * @return array
   */
  abstract function toDatabase();


  abstract function save();
  abstract function delete();
  abstract function update();

  /**
   * @return DunnArray<T>
   */
  abstract function getAll();

  /**
   * @return T | null
   */
  abstract function getById();

  function getValuesWithoutNull($arr)
  {
    foreach ($arr as $key => $value) {
      if (is_null($value)) {
        unset($arr[$key]);
      }
    }
    return $arr;
  }

  function getTableName()
  {
    return $this->tableName;
  }

  /**
   * @return DunnMap<string>
   */
  function validate()
  {
    $data = $this->toDatabase();
    $errors = [];
    foreach ($data as $key => $value) {
      if (is_null($value)) {
        $errors[$key] = "Field $key is required";
      }
    }
    return new DunnMap($errors);
  }
}
