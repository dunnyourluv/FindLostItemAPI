<?php

namespace DUVX\Models\Builders;
use DUVX\Models\UserModel;

class UserBuilder
{
  private $uuid;
  private $username;
  private $password;
  private $email;
  private $isAdmin;
  private $avatar;
  private $createdAt;
  private $updatedAt;

  function uuid($id)
  {
    $this->uuid = $id;
    return $this;
  }

  function username($username)
  {
    $this->username = $username;
    return $this;
  }

  function password($password)
  {
    $this->password = $password;
    return $this;
  }

  function email($email)
  {
    $this->email = $email;
    return $this;
  }

  function name($name)
  {
    $this->name = $name;
    return $this;
  }

  function isAdmin($isAdmin)
  {
    $this->isAdmin = $isAdmin;
    return $this;
  }

  function avatar($avatar)
  {
    $this->avatar = $avatar;
    return $this;
  }

  function createdAt($createdAt)
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  function updatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  function fromArray($arr)
  {
    $this->username = $arr["username"];
    $this->password = $arr["password"];
    $this->email = $arr["email"];
    $this->avatar = $arr["avatar"] ?? null;
    $this->isAdmin = $arr['is_admin'] === 1;
    $this->createdAt = $arr["created_at"];
    $this->updatedAt = $arr["updated_at"];
    $this->uuid = $arr["uuid"];
    return $this;
  }

  function build()
  {
    return new UserModel($this->uuid, $this->username, $this->password, $this->email, $this->isAdmin, $this->avatar, $this->createdAt, $this->updatedAt);
  }
}
