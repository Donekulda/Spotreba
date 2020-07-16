<?php
  session_start();
  error_reporting(-1);
  ini_set('display_errors', 'On');
  require_once "../config.inc.php";
  require_once "../functions.inc.php";
  
  if(count($_COOKIE) <= 0) {
    if(isset($_SESSION['cookie_error_count']) && $_SESSION['cookie_error_count']==true){ 
      die ("Povolte v nastavení Cookies! A refreshnete stránku! PS: může se stát že máte cookies povolené tak projistotu kdyžtak také refreshněte stránku!");
    };
    $_SESSION['cookie_error'] = true;
    header("Refresh:0");
  }else{
    $_SESSION['cookie_error'] = false;
  }
  
  if(!IsLogged($title))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") < 2){
    header("location: ../error.php");
  }
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
     
  require_once '../plugins/PHPMailer/src/Exception.php';
  require_once '../plugins/PHPMailer/src/PHPMailer.php';
  require_once '../plugins/PHPMailer/src/SMTP.php';
  

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta name="description" content="Adminská sekce pro spotreba.solarnivyroba.cz">
    <meta name="author" content="Mikuláš Staněk">
    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
    
    <title>Admin sekce - <?php echo $title; ?></title>
  </head>
  <body>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <div class="templatemo-flex-row">
    <?php 
      require_once "./menu.inc.php";
    ?>