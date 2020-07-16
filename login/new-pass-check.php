<?php
session_start();
error_reporting(-1);
ini_set('display_errors', 'On');

require_once "../config.inc.php";
require_once "../functions.inc.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
   
require_once '../plugins/PHPMailer/src/Exception.php';
require_once '../plugins/PHPMailer/src/PHPMailer.php';
require_once '../plugins/PHPMailer/src/SMTP.php';

$hesloObnoveno = false;

if(isset($_POST['zpet'])){
  header("location: ./");
}

if(isset($_GET['recovery']) && isset($_GET['id'])){
  if(GetVariable($_GET['id'], "uzivatele", "recovery_kod") == $_GET['recovery'] && GetVariable($_GET['id'], "uzivatele", "recovery_kod") != NULL){
    $id = $_GET['id']; 
    $jmeno = GetVariable($id, "uzivatele", "Jmeno");
    $prijmeni = GetVariable($id, "uzivatele", "Prijmeni");
    $email = GetVariable($id, "uzivatele", "email");
    $nick = GetVariable($id, "uzivatele", "nick");

    $heslo = GenerateKey(14);
    $sifrovane = password_hash($heslo,PASSWORD_DEFAULT);
    ChangeString($_GET['id'], "uzivatele", "heslo", $sifrovane);
    ChangeString($_GET['id'], "uzivatele", "recovery_kod", NULL);

    $array = array("%heslo" => $heslo, "%jmeno" => $jmeno, "%prijmeni" => $prijmeni, "%nick"=>$nick);
    System_mail($email, $jmeno, $prijmeni, 4, "Zapomenuté heslo část 2.", $array);
    $hesloObnoveno = true;
  }
}
?>
<!DOCTYPE html>
<html lang="cz">

  <head>
    <title>Obnovení hesla 2.část</title>
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
              <i class="fas fa-envelope-open"></i>
            </span>

            <span class="login100-form-title p-b-25 p-t-27">
              Obnova hesla 2.část
            </span>
            
            <div class="text-center p-t-10">
              <?php if($hesloObnoveno){ ?>
                <p class="txt1">Na tvůj email ti byl zaslán mail s tvým nové vygenerovaným heslem!</p>
              <?php 
                }else{
              ?>
                <p class="txt1">Odkaz nefungoval, musíš kliknout/oteřít odkaz který ti byl zaslán!. A nebo tvůj odkaz byl již použit.</p>
              <?php 
                }
              ?>
            </div>

            <div class="container-href-form-btn p-t-25">
              <button type="submit" name="zpet" class="href-form-btn" onclick="window.location.href = './';">
                <i class='fas fa-unlock'></i>&nbsp;&nbsp;Přihlašovací stránka
              </button>
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