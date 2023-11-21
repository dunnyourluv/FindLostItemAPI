<?php

namespace DUVX\Utils;

class Database extends \PDO
{
  private function __construct($dsn, $username = null, $password = null, $options = null)
  {
    parent::__construct($dsn, $username, $password, $options);
  }

  static function connect()
  {
    $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8mb4';
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $options = [
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new self($dsn, $username, $password, $options);
  }

  function run($sql, $args = null)
  {
    if (!$args) {
      return $this->query($sql);
    }
    $stmt = $this->prepare($sql);
    $stmt->execute($args);
    return $stmt;
  }

  public function insert($table, $data)
  {
    $keys = implode(', ', array_keys($data));
    $values = ':' . implode(', :', array_keys($data));

    $sql = "INSERT INTO $table ($keys) VALUES ($values)";
    $stmt = $this->prepare($sql);

    foreach ($data as $key => $value) {
      $stmt->bindValue(":$key", $value);
    }

    return $stmt->execute();
  }

  public function update($table, $data, $condition, $conditionParams = array())
  {
    $setClause = '';

    foreach ($data as $key => $value) {
      if (isset($value)) {
        $setClause .= "$key = :$key, ";
      }
    }

    if (!empty($setClause)) {
      $setClause = rtrim($setClause, ', ');

      $sql = "UPDATE $table SET $setClause WHERE $condition";
      $stmt = $this->prepare($sql);

      foreach ($data as $key => $value) {
        if (isset($value)) {
          $stmt->bindValue(":$key", $value);
        }
      }

      foreach ($conditionParams as $key => $value) {
        $stmt->bindValue(":$key", $value);
      }

      return $stmt->execute();
    }

    return false;
  }

  public function delete($table, $condition, $conditionParams = array())
  {
    $sql = "DELETE FROM $table WHERE $condition";
    $stmt = $this->prepare($sql);

    foreach ($conditionParams as $key => $value) {
      $stmt->bindValue(":$key", $value);
    }

    return $stmt->execute();
  }
}
