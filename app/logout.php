<?php
  session_start();
  unset($_SESSION['id']);
  session_destroy();
  
  $adresa="http://spotreba.solarnivyroba.cz";

  $cookie_name = "login-cookie";
  if (isset($_COOKIE[$cookie_name])) {
    unset($_COOKIE[$cookie_name]); 
    setcookie($cookie_name, null, -1, '/'); 
    header('Location: '.$adresa.'/login');
  } else {
    header('Location: '.$adresa.'/login');
  }
?>