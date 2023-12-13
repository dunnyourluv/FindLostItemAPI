<?php

namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DUVX\Exceptions\UserException;
use DUVX\Models\Builders\UserBuilder;
use DUVX\Utils\Database;
use DUVX\Utils\FieldValidate;
use DUVX\Utils\StringBuilder;

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

  /**
   * @return UserBuilder
   */
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
      "avatar" => str_starts_with($this->avatar, '/') ? 'http://' . $_SERVER['HTTP_HOST'] . $this->avatar : $this->avatar,
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
    $db->insert($this->tableName, $data);
    return $this->getById();
  }
  function update()
  {
    $db = Database::connect();
    $data = $this->getValuesWithoutNull($this->toDatabase());
    $updated = $db->update($this->tableName, $data, 'uuid = :uuid', ['uuid' => $this->uuid]);
    return $updated ? $this->getById() : null;
  }

  function updateFromUser(UserModel $user)
  {
    $this->username = $user->username;
    $this->email = $user->email;
    $this->isAdmin = $user->isAdmin;
    $this->avatar = $user->avatar;
    $this->password = $user->password;

    if ($this->email && !FieldValidate::validateEmail($this->email))
      throw new UserException('Email is invalid', 400);
    if ($this->username && !FieldValidate::validateUsername($this->username))
      throw new UserException('Username is invalid', 400);
    if ($this->password && !FieldValidate::validatePassword($this->password))
      throw new UserException('Password is invalid', 400);

    return $this->update();
  }

  function delete()
  {
    $avatarRootPath = $_SERVER['DOCUMENT_ROOT'] . $this->avatar;
    if (file_exists($avatarRootPath)) {
      unlink($avatarRootPath);
    }
    $db = Database::connect();
    return $db->delete($this->tableName, 'uuid = :uuid', ['uuid' => $this->uuid]);
  }

  /**
   * @return \DunnServer\Utils\DunnArray<UserModel>
   */
  function getAll($limit = 0, $offset = 0)
  {
    $db = Database::connect();
    $sql = new StringBuilder();
    $sql->append('SELECT * FROM ' . $this->tableName);
    if ($limit > 0) {
      $sql->append(' LIMIT ' . $limit);
    }
    if ($offset > 0) {
      $sql->append(' OFFSET ' . $offset);
    }
    $stm = $db->run($sql->toString());
    $data = new DunnArray(...$stm->fetchAll());
    return $data->map(function ($item) {
      return static::builder()->fromArray($item)->build();
    });
  }
  function getById()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->tableName . ' WHERE uuid = ?', [$this->uuid]);
    $data = $stm->fetch();
    return $data ? static::builder()->fromArray($data)->build() : null;
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

  function getByUsername()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->tableName . ' WHERE username = ?', [$this->username]);
    $data = $stm->fetch();
    return $data ? static::builder()->fromArray($data)->build() : null;
  }

  function has()
  {
    $db = Database::connect();
    $stm = $db->run('SELECT * FROM ' . $this->tableName . ' WHERE username = ? OR email = ?', [$this->username, $this->email]);
    $data = $stm->fetch();
    return $data ? true : false;
  }

  function validate()
  {
    $errors = parent::validate();
    $emailPattern = '/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/';
    if (!preg_match($emailPattern, $this->email)) {
      $errors->set('email', 'Email is invalid!');
    }

    if (strlen($this->username) < 6) {
      $errors->set('username', 'Username must be at least 6 characters!');
    }

    if (strlen($this->password) < 6) {
      $errors->set('password', 'Password must be at least 6 characters!');
    }

    return $errors;
  }
}
