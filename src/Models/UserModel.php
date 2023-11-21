<?php

namespace DUVX\Models;

use DUVX\Models\Builders\UserBuilder;
use DUVX\Utils\Database;

/**
 * @extends Model<UserModel>
 */
class UserModel extends Model
{
  private $uuid;
  private $username;
  private $password;
  private $email;
  private $isAdmin;
  private $avatar;
  private $createdAt;
  private $updatedAt;

  function __construct($uuid, $username, $password, $email, $isAdmin, $avatar, $createdAt, $updatedAt)
  {
    parent::__construct('users');
    $this->uuid = $uuid;
    $this->username = $username;
    $this->password = $password;
    $this->email = $email;
    $this->isAdmin = $isAdmin;
    $this->avatar = $avatar;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
  }

  static function builder()
  {
    return new UserBuilder();
  }

  function jsonSerialize(): mixed
  {
    return [
      "uuid" => $this->uuid,
      "username" => $this->username,
      "email" => $this->email,
      "isAdmin" => $this->isAdmin,
      "avatar" => $this->avatar,
      'createdAt' => $this->createdAt,
      'updatedAt' => $this->updatedAt
    ];
  }

  function getUsername()
  {
    return $this->username;
  }

  function getPassword()
  {
    return $this->password;
  }

  function getUuid()
  {
    return $this->uuid;
  }

  function getEmail()
  {
    return $this->email;
  }

  function getIsAdmin()
  {
    return $this->isAdmin;
  }

  function getAvatar()
  {
    return $this->avatar;
  }

  function getCreatedAt()
  {
    return $this->createdAt;
  }

  function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  function setUsername($username)
  {
    $this->username = $username;
  }

  function setPassword($password)
  {
    $this->password = $password;
  }

  function setUuid($uuid)
  {
    $this->uuid = $uuid;
  }

  function setEmail($email)
  {
    $this->email = $email;
  }

  function setIsAdmin($isAdmin)
  {
    $this->isAdmin = $isAdmin;
  }

  function setAvatar($avatar)
  {
    $this->avatar = $avatar;
  }

  function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;
  }

  function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;
  }

  function save()
  {
    $db = Database::connect();
    $data = $this->toDatabase();
    $data['uuid'] = $this->uuid;
    return $db->insert($this->tableName, $data);
  }
  function update()
  {
    $db = Database::connect();
    $data = $this->getValuesWithoutNull($this->toDatabase());
    return $db->update($this->tableName, $data, 'uuid = :uuid', ['uuid' => $this->uuid]);
  }
  function delete()
  {
    $db = Database::connect();
    return $db->delete($this->tableName, 'uuid = :uuid', ['uuid' => $this->uuid]);
  }

  /**
   * @return UserModel[]
   */
  function getAll()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM '. $this->tableName);
    $data = $stm->fetchAll();
    return array_map(function ($item) {return static::builder()->fromArray($item)->build();}, $data);
  }
  function getById($id)
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM'. $this->tableName .'WHERE uuid = ?', [$id]);
    $data = $stm->fetch();
    return static::builder()->fromArray($data)->build();
  }

  function toDatabase()
  {
    return [
      "username" => $this->username,
      "password" => $this->password,
      "email" => $this->email,
      "is_admin" => $this->isAdmin,
      "avatar" => $this->avatar,
    ];
  }
}
