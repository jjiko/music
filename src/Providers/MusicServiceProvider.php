<?php

namespace Jiko\Music\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class MusicServiceProvider extends ServiceProvider
{
  public function boot()
  {

    parent::boot();
  }

  public function register()
  {

  }

  public function map(Router $router)
  {
    require_once(__DIR__.'/../Http/routes.php');
  }
}