<?php

namespace DUVX\Router;

use DunnServer\Router\Router;
use DUVX\Controllers\ProfileMeCtl;
use DUVX\Controllers\UserProfileCtl;
use DUVX\Middlewares\AuthFilter;

class ProfileRouter extends Router
{
  function __construct()
  {
    parent::__construct('/profiles');
    $this->addRoute('/me', new ProfileMeCtl());
    $this->addRoute('/{username}', new UserProfileCtl());
    // Add Filters
    $this->addFilter('/me', new AuthFilter());
  }
}
