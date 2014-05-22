<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="UTF-8">
  <title>Page not found 404</title>

  <meta name="viewport" content="width=device-width">

  <meta name="description" content="dota replay parser">
  <meta name="keywords" content="warcraft,dota,games,savereplay">

  <link rel="stylesheet" type="text/css" href="/stylesheets/albatross_api.css" />

</head>
<body class="page404">

  <div class="page">
    <header>
      <a class="logo" href="/">w3xSilverCloud</a>
      <nav class="main-nav">
        <a href="/">Home</a>
        <a href="/upload">Upload replay</a>
        <a href="/view">Replay database</a>
        <a href="/news">News</a>
        <a href="/contact">Contact</a>
        <a href="https://github.com/dexterns88/w3xsilvercloude/issues" target="_blank">Report issue</a>
      </nav>
    </header> <!-- header -->

    <div class="region-content">
      
      <?php
        print_r($content) 
      ?>
    </div> <!-- region-content -->
    
    <footer class="region-footer">
      <div class="footer-inner">
        <p>copyright 2014 by <a target="_blank" href="http://webpage-lab.com">Webpage-lab.com</a></p>
      </div>
    </footer> <!-- footer -->

  </div> <!-- page -->

  <div id="script" style="display: none; visibility: hidden;">
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.0.2.min.js"></script>
    <script>
      $('html').removeClass('no-js').addClass('js');
    </script>
    <script type="text/javascript" src="/script/api.js"></script>
  </div>

</body>
</html>