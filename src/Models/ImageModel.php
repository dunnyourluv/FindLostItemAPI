<?php

namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\ImageException;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;

/**
 * @extends Model<ImageModel>
 */
class ImageModel extends Model
{
  private $uuid;
  private $postUuid;
  private $image;
  private $createdAt;
  private $updatedAt;

  function __construct($uuid = null, $postUuid = null, $image = null, $createdAt = null, $updatedAt = null)
  {
    parent::__construct('post_images');
    $this->uuid = $uuid;
    $this->postUuid = $postUuid;
    $this->image = $image;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  function toDatabase()
  {
    return [
      'uuid' => $this->uuid,
      'post_uuid' => $this->postUuid,
      'image' => $this->image,
    ];
  }

  function save()
  {
    $data = $this->toDatabase();
    if (!$data['uuid'])
      $data['uuid'] = uniqid();
    $errors = $this->validate();
    if ($errors->length() > 0)
      throw new ImageException($errors->values()->join(', '), 400);
    $db = Database::connect();

    $db->insert($this->getTableName(), $data);
    return $this->getById();
  }

  function update()
  {
    if (!$this->has())
      throw new ImageException('Image not found', 404);
    $db = Database::connect();
    $data = $this->toDatabase();
    unset($data['uuid']);
    unset($data['post_uuid']);
    $updated = $db->update($this->getTableName(), $data, ['uuid' => $this->uuid]);
    return $updated ? $this->getById() : null;
  }

  function delete()
  {
    if (!$this->has())
      throw new ImageException('Image not found', 404);
    $db = Database::connect();
    $deleted = $db->delete($this->getTableName(), ['uuid' => $this->uuid]);
    return $deleted ? $this->getById() : null;
  }

  function getAll($limit = 0, $offset = 0)
  {
    $db = Database::connect();
    $sql = new StringBuilder();
    $sql->append('SELECT * FROM ')->append($this->getTableName());
    if ($limit > 0)
      $sql->append(' LIMIT ')->append($limit);
    if ($offset > 0)
      $sql->append(' OFFSET ')->append($offset);
    $stm = $db->query($sql->toString());

    $data = new DunnArray(...$stm->fetchAll());

    return $data->map(function ($image) {
      return self::fromDatabase($image);
    });
  }

  /**
   * @return DunnArray<ImageModel>
   */
  function getsWithPostUuid()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->getTableName() . ' WHERE post_uuid = :post_uuid', ['post_uuid' => $this->postUuid]);
    $data = new DunnArray(...$stm->fetchAll());
    return $data->map(function ($image) {
      return self::fromDatabase($image);
    });
  }

  function getById()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->getTableName() . ' WHERE uuid = :uuid', ['uuid' => $this->uuid]);
    $data = $stm->fetch();
    return $data ? self::fromDatabase($data) : null;
  }

  function jsonSerialize(): mixed
  {
    return [
      'uuid' => $this->uuid,
      'url' => str_starts_with($this->image, '/') ? 'http://' . $_SERVER['HTTP_HOST'] . $this->image : $this->image,
      'createdAt' => $this->createdAt,
      'updatedAt' => $this->updatedAt,
    ];
  }

  static function fromDatabase($data)
  {
    return new ImageModel($data['uuid'], $data['post_uuid'], $data['image'], $data['created_at'], $data['updated_at']);
  }

  function has()
  {
    return $this->getById() != null;
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
   * Get the value of postUuid
   */
  public function getPostUuid()
  {
    return $this->postUuid;
  }

  /**
   * Set the value of postUuid
   *
   * @return  self
   */
  public function setPostUuid($postUuid)
  {
    $this->postUuid = $postUuid;

    return $this;
  }

  /**
   * Get the value of image
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * Set the value of image
   *
   * @return  self
   */
  public function setImage($image)
  {
    $this->image = $image;

    return $this;
  }

  /**
   * Get the value of createdAt
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Set the value of createdAt
   *
   * @return  self
   */
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get the value of updatedAt
   */
  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  /**
   * Set the value of updatedAt
   *
   * @return  self
   */
  public function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}
