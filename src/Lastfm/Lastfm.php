<?php namespace Jiko\Music\Lastfm;

use Jiko\Api\CacheableApiTrait;

class Lastfm
{
  use CacheableApiTrait;

  protected $endpoint = 'https://ws.audioscrobbler.com/2.0/';

  public function get($key='recent', $opt=[])
  {
    return method_exists($this, $key) ? $this->{$key}($opt) : false;
  }

  public function recent($opt=[])
  {
    $page = array_get($opt, 'page', 1);
//    if($cache = self::readCache('lastfm.recenttracks.' . $page)) {
//      if($data = self::cacheIsFresh($cache)) {
//        return $data;
//      }
//    }

    $querystring = http_build_query([
      'method' => 'user.getrecenttracks',
      'user' => 'joejiko',
      'api_key' => getenv('LASTFM_API_KEY'),
      'format' => 'json'
    ]);

    if($data = getJson("{$this->endpoint}?$querystring")) {
      //$cache->update(['data' => json_encode($data)]);

      return $data;
    }

    return false;
  }
}