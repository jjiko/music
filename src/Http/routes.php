<?php
Route::get('lastfm/recent', function () {
  dd((new Jiko\Lastfm\Lastfm)->recent_tracks());
});