<?php
session_start();
//error_reporting(-1);
//ini_set('display_errors', 'On');

require_once "../config.inc.php";
require_once "../functions.inc.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
   
require_once '../plugins/PHPMailer/src/Exception.php';
require_once '../plugins/PHPMailer/src/PHPMailer.php';
require_once '../plugins/PHPMailer/src/SMTP.php';

//$recaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Ldn6qYUAAAAADtT7BVrfynf4ceDAE1Didhh-QwX&response=' . $_POST['g-recaptcha-response'])); 

if(isset($_POST['email'])){
  $email = $_POST['email'];
  if(DoesExist("uzivatele" , "email", $_POST['email'])){
    $id = GetId("uzivatele", "email", $_POST['email']);
    $jmeno = GetVariable($id, "uzivatele", "Jmeno");
    $prijmeni = GetVariable($id, "uzivatele", "Prijmeni");
    $nick = GetVariable($id, "uzivatele", "nick");

    $recovery = GenerateKey(25);
    ChangeString($id, "uzivatele", "recovery_kod", $recovery);
    $URL = $adresa."/login/new-pass-check.php?id=".$id."&recovery=".$recovery;

    $array = array("%url"=>$URL, "%jmeno"=>$jmeno, "%prijmeni"=>$prijmeni, "%nick"=>$nick);
    System_mail($email, $jmeno, $prijmeni, 3, "Zapomenuté heslo část 1.", $array); 
  }
}

?>
<!DOCTYPE html>
<html lang="cz">
  
  <head>
    <title>Obnova hesla</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
  </head>
  <body>
    <div class="limiter">
      <div class="container-login100" style="background-image: url('images/bg-01.jpg');">
        <div class="wrap-login100">
          <form id="login_form" method = "post" action="" class="login100-form validate-form">
            <span class="login100-form-logo">
              <i class="fas fa-envelope"></i>
            </span>

            <span class="login100-form-title p-b-34 p-t-27">
              Obnova hesla
            </span>
            
            <div class="text-center p-t-10">
              <?php if(!isset($_POST['email'])){ ?>
                <p class="text" style="color:white;">Zadejte vaši e-mailovu adresu!</p>
              <?php 
                }else{
              ?>
                <p class="text" style="color:white;">Pokud účet s touto e-mailovou adresou existuje tak email byl odeslán s ověřovacími informacemi!</p>
              <?php 
                }
              ?>
            </div>

            <div class="wrap-input100 validate-input" data-validate="Zadej email">
              <input class="input100" type="email" name="email" placeholder="Email">
              <span class="focus-input100" data-placeholder="&#xf15a;"></span>
            </div>

            <div class="container-login100-form-btn">
              <button class="login100-form-btn">
                Odeslat
              </button>
            </div>

            <div class="text-center p-t-90">
              <a class="txt1" href="./">
                Zpátky na přihlašovací stránku?
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>


    <div id="dropDownSelect1"></div>

    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>

</html>
<?php
  $_SESSION['connection']->close();
?>