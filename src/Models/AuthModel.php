<?php

namespace DUVX\Models;

use DUVX\Exceptions\AuthException;
use DUVX\Utils\Database;

class AuthModel
{
  private UserModel $model;
  function __construct($model)
  {
    $this->model = $model;
  }

  function login()
  {
    $username = $this->model->getUsername();
    $password = $this->model->getPassword();
    $db = Database::connect();
    $stm = $db->run("SELECT * FROM " . $this->model->getTableName() . " WHERE username = ?", [$username]);
    $data = $stm->fetch();
    if ($data) {
      $user = UserModel::builder()->fromArray($data)->build();
      if ($user->getPassword() != $password)
        throw new AuthException('Invalid password', 400);
      return $user;
    } else
      throw new AuthException('Username does not exists!', 400);
  }

  /**
   * @return UserModel
   */
  function register()
  {
    $errors = $this->model->validate();
    if($errors->length() > 0){
      $mess = $errors->values()->join(', ');
      throw new AuthException($mess, 400);
    }

    if($this->model->has()) throw new AuthException('Username or email already exists!', 500);
    $user = $this->model->save();
    return $user;
  }
}
