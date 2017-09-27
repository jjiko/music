<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Recently played tracks</h1>
        </div>
        <div class="col-md-12">
          <?php $pi = 0; ?>
            @foreach($data->recenttracks->track as $track)
                <div class="row" data-mbid="{{ $track->mbid }}" data-streamable="<?php echo $track->streamable; ?>" style="border-bottom: 1px solid #ccc; margin-bottom: 5px;">
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
                    <div class="col-xs-2"><img src="{{ $track->image[0]->{"#text"} }}" alt="X"></div>
                    <div class="col-xs-8">
                        <a href="{{ $track->url }}" target="_blank"><?php echo $track->artist->{"#text"}; ?>
                            — <?php echo $track->name;  ?></a><br>
                        @if(array_key_exists("@attr", $track))
                            Now Playing<br>
                        @else
                        <?php $date = new \Carbon\Carbon($track->date->{"#text"}); echo $date->diffForHumans(); ?><br>
                        @endif
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
                            <audio controls style="width:100%">
                                <source src="{{ $sTrack->preview_url }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @endif
                    </div>
                    @if(property_exists($sTrack, "external_urls"))
                        <div class="col-xs-2 text-right">
                            <a href="{{ $sTrack->external_urls->spotify }}"
                               target="_blank"><img width="24"
                                                    height="24"
                                                    src="https://d2c87l0yth4zbw-2.global.ssl.fastly.net/i/_global/favicon.png"></a>
                        </div>
                    @else
                        <div class="col-xs-2 text-right"><a
                                    href="https://open.spotify.com/search/results/{{ urlencode(sprintf("%s %s", $track->artist->{"#text"}, $track->name)) }}"><i
                                        class="fa fa-search"></i></a></div>
                    @endif
                  <?php } catch (\Exception $e) {
                  }
                  }
                  else {
                  ?>
                </div>
                <div class="col-xs-2 text-right">
                    <a href="https://open.spotify.com/search/results/{{ sprintf("%s %s", $track->artist->{"#text"}, $track->name)}}"
                       target="_blank"><i
                                class="fa fa-search" style="font-size:24px"></i></a>
                </div>
            <?php
            }
            $pi++;
            ?>
        </div>
        @endforeach
    </div>
</div>
<p>Powered by <a href="{{ shorten('https://www.last.fm/user/joejiko/library') }}"
                 target="_blank">last.fm</a></p>
</div>
</div>
</div>