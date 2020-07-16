<?php 
   session_start();
   
    require_once "./config.inc.php";
    require_once "./functions.inc.php";
    
    
    if(count($_COOKIE) <= 0) {
      echo "Povolte v nastavení Cookies!";
    }
?>
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  <link rel="stylesheet" href="http://spotreba.solarnivyroba.cz/vzhled/new_style.css">
  <title>Error Page</title>
  </head>
  <body>
  <?php 
    require_once "./menu.php";
  ?>
    <div class="isa_error">
      <i class="fa fa-times-circle"></i>
      <h1>Stránka není dostupná</h1>
      <h3>Buďto z technický důvodů a nebo ze špatného zadání adresy není stránka v tuto chvíly dostupná!</h3>
    </div>
  </body>
</html>