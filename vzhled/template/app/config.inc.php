<?php
  $db_host = "localhost";
  $db_user = "solspotreb.11346";
  $db_pass = "PLfUbHj2rUVH7Wj2";
  $db_db = "solspotreb_11346";
  
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_db);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  mysqli_set_charset($conn,"utf8");
  
  $_SESSION['connection'] = $conn; 
  
  $adresa = "http://spotreba.solarnivyroba.cz/";
   
  $default_port = 80;
?>