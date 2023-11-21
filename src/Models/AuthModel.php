<?php

namespace DUVX\Models;
use DUVX\Exceptions\AuthException;
use DUVX\Utils\Database;

class AuthModel {
  private UserModel $model;
  function __construct($model)
  {
    $this->model = $model;
  }

  function login()
  {
    $username = $this->model->getUsername();
    $password =$this->model->getPassword();
    $db = Database::connect();
    $stm = $db->run("SELECT * FROM ".$this->model->getTableName()." WHERE username = ?", [$username]);
    $data = $stm->fetch();
    if($data)
    {
      $user = UserModel::builder()->fromArray($data)->build();
      if($user->getPassword() != $password) throw new AuthException('Invalid password', 400);
      return $user;
    }else throw new AuthException('Username does not exists!', 400);
  }
}
