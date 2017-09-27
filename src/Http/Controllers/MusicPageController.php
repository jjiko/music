<?php namespace Jiko\Music\Http\Controllers;

use Jiko\Auth\User;
use Jiko\Http\Controllers\Controller;
use Jiko\Music\Lastfm\Lastfm;

class MusicPageController extends Controller
{
  public function index()
  {
    $lastfm = new Lastfm();
    $data = $lastfm->get();
    $spotifyUser = User::find(2)->spotify;
    if ($this->agent->isMobile() || $this->agent->isTablet()) {
      \Session::put('device', 'mobile');
      $this->layout = view($this->mobileLayout);
      return $this->content('music::mobile.recent', ['data' => $data, 'token' => $spotifyUser->getToken()]);
    }
    $this->content('music::recent', ['data' => $data, 'token' => $spotifyUser->getToken()]);
  }
}