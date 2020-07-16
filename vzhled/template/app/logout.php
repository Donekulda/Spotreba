<?php
  session_start();
  unset($_SESSION['id']);
  session_destroy();
  
  $adresa="http://spotreba.solarnivyroba.cz";
  $cookie_name = "login-cookie";
  unset($_COOKIE[$cookie_name]);
  // empty value and expiration one hour before
  $res = setcookie($cookie_name, '', time() - 3600);
  // Redirect to the login page:
  header('Location: '.$adresa.'/login');
?>