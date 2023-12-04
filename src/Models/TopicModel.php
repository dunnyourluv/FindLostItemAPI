<?php

namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\TopicException;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;

class TopicModel extends Model
{
  private $uuid;
  private $name;

  public function __construct($uuid = null, $name = null)
  {
    parent::__construct('post_topic');
    $this->uuid = $uuid;
    $this->name = $name;
  }

  function toDatabase()
  {
    return [
      'uuid' => $this->uuid,
      'name' => $this->name
    ];
  }

  function save()
  {
    $db = Database::connect();
    $data = $this->toDatabase();
    if ($data['uuid'] == null) {
      $id = uniqid();
      $data['uuid'] = $id;
      $this->uuid = $id;
    }

    $errors = $this->validate();

    if ($errors->length() > 0) {
      $mess = $errors->values()->join(', ');
      throw new TopicException($mess, 400);
    }

    $db->insert($this->getTableName(), $data);
    return $this->getById();
  }

  function update()
  {
    if (!$this->has()) {
      throw new TopicException('Topic not found!', 404);
    }
    $db = Database::connect();
    $data = $this->toDatabase();
    if ($data['uuid'])
      $data['uuid'] = null;
    $data = $this->getValuesWithoutNull($data);
    $updated = $db->update($this->getTableName(), $data, 'uuid = :uuid', ['uuid' => $this->uuid]);
    return $updated ? $this->getById() : null;
  }

  function delete()
  {
    if (!$this->has()) {
      throw new TopicException('Topic not found!', 404);
    }
    $db = Database::connect();
    $deleted = $db->delete($this->getTableName(), 'uuid = :uuid', ['uuid' => $this->uuid]);
    return $deleted;
  }

  function getAll($limit = 0, $offset = 0)
  {
    $db = Database::connect();
    $sql = new StringBuilder();
    $sql->append('SELECT * FROM ' . $this->getTableName());
    if ($limit > 0) {
      $sql->append(' LIMIT ' . $limit);
    }
    if ($offset > 0) {
      $sql->append(' OFFSET ' . $offset);
    }

    $stm = $db->run($sql->toString());

    $data = new DunnArray(...$stm->fetchAll());
    return $data->map(function ($item) {
      return new TopicModel($item['uuid'], $item['name']);
    });
  }

  function getById()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->getTableName() . ' WHERE uuid = ?', [$this->uuid]);
    $data = $stm->fetch();
    return $data ? new TopicModel($data['uuid'], $data['name']) : null;
  }

  function has()
  {
    return $this->getById() != null;
  }

  function jsonSerialize(): mixed
  {
    return [
      'uuid' => $this->uuid,
      'name' => $this->name
    ];
  }

  /**
   * Get the value of uuid
   */
  public function getUuid()
  {
    return $this->uuid;
  }

  /**
   * Set the value of uuid
   *
   * @return  self
   */
  public function setUuid($uuid)
  {
    $this->uuid = $uuid;

    return $this;
  }

  /**
   * Get the value of name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }
}
