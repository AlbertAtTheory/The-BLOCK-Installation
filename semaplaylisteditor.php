<?php
  $fn = "semaplaylist.txt";
  
  if (isset($_POST['content'])) {
    $content = stripslashes($_POST['content']);
    $fp = fopen($fn,"w") or die ("Error opening file in write mode!");
    fputs($fp,$content);
    fclose($fp) or die ("Error closing file!");
  }
?>
<!DOCTYPE html>

<html class="no-js" dir="ltr" lang="en">

  <head>
    <title>SEMA Playlist Editor</title>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="width = device-width, initial-scale = 1.0, maximum-scale = 1.0, minimum-scale = 1.0, user-scalable = no" name="viewport">
    <link href="//d23tod3mb75lgr.cloudfront.net/1349780241/assets/viso-datauri.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="//d23tod3mb75lgr.cloudfront.net/1349780241/assets/viso.js" type="text/javascript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">
      function loadSemaFile() {
        $.get('https://gdata.youtube.com/feeds/api/playlists/PLAxgpZrkmEiYpu9ruxb500rUwBex8OLil?alt=jsonc&v=2', function(data) {
          $('#file').val(data);
        }, 'text');
      }
    </script>
  </head>

  <body id="other">
    <div class="wrapper" style="width: 650px;">
      <section id="content">
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
          <p><a href="javascript:loadSemaFile();" class="downloadlink">Download</a></p>
          <textarea rows="25" cols="80" name="content" id="file"><?php readfile($fn); ?></textarea>
          <p><input type="submit" value="Save File"></p>
        </form>
      </section>
    </div>
  </body>

</html>
