<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Recently played tracks</h1>
        </div>
        <div class="col-md-12">
            <table class="table table-condensed">
              <?php $pi = 0; ?>
                @foreach($data->recenttracks->track as $track)
                    <tr data-mbid="{{ $track->mbid }}" data-streamable="<?php echo $track->streamable; ?>">
                        <script type="application/ld+json">
                            {
                              "@context": "http://schema.org",
                              "@type": "MusicAlbum",
                              "byArtist": {
                                "@type": "MusicGroup",
                                "name": "<?php echo $track->artist->{"#text"}; ?>"
                              },
                              "image": "<?php echo $track->image[3]->{"#text"}; ?>",
                              "name": "<?php echo $track->album->{"#text"}; ?>",
                              "track": [
                                {
                                  "@type": "MusicRecording",
                                  "name": "<?php echo $track->name; ?>",
                                  "url": "<?php echo $track->url; ?>"
                                }
                              ]
                            }

                        </script>
                        <td><img src="{{ $track->image[0]->{"#text"} }}" alt="X"></td>
                        <td><a href="{{ $track->url }}" target="_blank"><?php echo $track->artist->{"#text"}; ?>
                                — <?php echo $track->name;  ?></a></td>
                        <td>
                            @if(array_key_exists("@attr", $track))
                                Now Playing
                            @else
                            <?php $date = new \Carbon\Carbon($track->date->{"#text"}); echo $date->diffForHumans(); ?>
                            @endif
                        </td>
                        <td>
                          <?php
                          if ($pi < 6) {
                          try {
                          $client = new GuzzleHttp\Client();
                          $res = $client->request('GET', 'https://api.spotify.com/v1/search?' . http_build_query(['q' => sprintf("track:%s artist:%s", $track->name, $track->artist->{"#text"}), 'type' => 'track']), [
                            'headers' => [
                              'Accept' => 'application/json',
                              'Authorization' => 'Bearer ' . $token
                            ]
                          ]);
                          $sData = json_decode($res->getBody());
                          $sTrack = array_first($sData->tracks->items);
                          ?>
                            @if(property_exists($sTrack, 'preview_url'))
                                <audio controls>
                                    <source src="{{ $sTrack->preview_url }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endif
                        </td>
                        <td>
                            @if(property_exists($sTrack, "external_urls"))
                                <a href="{{ $sTrack->external_urls->spotify }}" target="_blank"><img width="24"
                                                                                                     height="24"
                                                                                                     src="https://cdn.joejiko.com/img/upload/32/326dfa6c84225dfca443693e985fdaab.png"></a>
                        @endif
                      <?php } catch (\Exception $e) {
                      }
                      }
                      else {
                      ?>
                        </td>
                        <td>
                            <a href="https://open.spotify.com/search/results/{{ sprintf("%s %s", $track->artist->{"#text"}, $track->name)}}"
                               target="_blank"><i
                                        class="fa fa-search" style="font-size:24px"></i></a>
                          <?php
                          }
                          $pi++;
                          ?>
                        </td>
                    </tr>
                @endforeach
            </table>
            <p>Powered by <a href="{{ shorten('https://www.last.fm/user/joejiko/library') }}"
                             target="_blank">last.fm</a></p>
        </div>
    </div>
</div>