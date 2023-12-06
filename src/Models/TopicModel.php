<?php

namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\TopicException;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;

/**
 * @extends Model<TopicModel>
 */
class TopicModel extends Model
{
  private $uuid;
  private $name;

  private $createdAt;
  private $updatedAt;

  public function __construct($uuid = null, $name = null, $createdAt = null, $updatedAt = null)
  {
    parent::__construct('post_topic');
    $this->uuid = $uuid;
    $this->name = $name;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  function toDatabase()
  {
    return [
      'uuid' => $this->uuid,
      'name' => $this->name,
    ];
  }

  static function fromDatabase($data)
  {
    return new TopicModel($data['uuid'], $data['name'], $data['created_at'], $data['updated_at']);
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
      return self::fromDatabase($item);
    });
  }

  function getById()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->getTableName() . ' WHERE uuid = ?', [$this->uuid]);
    $data = $stm->fetch();
    return $data ? self::fromDatabase($data) : null;
  }

  function has()
  {
    return $this->getById() != null;
  }

  function jsonSerialize(): mixed
  {
    return [
      'uuid' => $this->uuid,
      'name' => $this->name,
      'createdAt' => $this->createdAt,
      'updatedAt' => $this->updatedAt,
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
