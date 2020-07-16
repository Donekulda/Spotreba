<?php
  $db_host = "uvdb46.active24.cz";
  $db_user = "spotrebaso";
  $db_pass = "Qo4gjHZ6BR";
  $db_db = "spotrebaso";
  
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_db);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  mysqli_set_charset($conn,"utf8");
?>