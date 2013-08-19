<iframe width="560" height="315" src="//www.youtube.com/embed/<?php echo $playlist['videos'][1]['id']; ?>?enablejsapi=1&playerapiid=playlist_block_youtube_player" frameborder="0" allowfullscreen id="playlist_block_youtube_player" class="player-state--1">
</iframe>
<script>
  var player;
  
  function onYouTubeIframeAPIReady() {
  	console.log('youtubeiframeapiready');
    player = new YT.Player('playlist_block_youtube_player', {
      events: {
        'onStateChange': onPlayerStateChange
      }
    });
  }

  function onPlayerStateChange(event) {
    jQuery('#playlist_block_youtube_player').attr('class','player-state-'+event.data);
  }
  
  function youTubePlayerPlay(id) {
    player.cueVideoById(id);
    player.playVideo();
  }
</script>
<?php
  $header = array('Thumbnail', 'Title', 'Likes');
  $rows = array();
  
  // Built the block using the data available in $playlist_data.
  foreach ($playlist['videos'] as $videos) {
    $thumbnail_image = array(
      'path' => $videos['thumbnail'],
      'alt' => $videos['title'],
      'title' => $videos['title'],
      'attributes' => NULL,
    );
    $thumbnail_image = theme_image($thumbnail_image);
    $link_address = 'javascript:youTubePlayerPlay(\'' . $videos['id'] . '\');';
    $rows[] = array(
      array(
        'data' => '<div class="thumbnail-trim">' . l($thumbnail_image, $link_address, array(
          'attributes' => array('target' => 'playlist_block_youtube_player'),
          'html' => TRUE,
          'external' => TRUE,
        )) . '</div>',
        'class' => 'youtube-thumbnail',
      ),
      array(
        'data' => l($videos['title'], $link_address, array(
          'attributes' => array('onclick' => 'youTubePlayerPlay(\'' . $videos['id'] . '\');'),
          'external' => TRUE,
          )),
        'class' => 'youtube-title',
      ),
      array(
        'data' => '<a href="https://www.facebook.com/sharer.php?u=http://youtu.be/' . $videos['id'] . '" target="_blank" class="facebook">Facebook</a><a href="https://twitter.com/share?url=http://youtu.be/' . $videos['id'] . '&related=ChevroletPerf" target="_blank" class="twitter">Twitter</a>',
        'class' => 'youtube-share',
      ),
    );
  }
      
  print theme_table(array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => NULL,
    'caption' => NULL,
    'colgroups' => NULL,
    'sticky' => TRUE,
    'empty' => t('No videos found in playlist.'),
  ));
?>
