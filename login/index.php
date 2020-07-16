<?php 
  session_start();
  
  function ULogged(){ // upravená verze IsLogged
    if(isset($_SESSION['connection'])){
      $conn = $_SESSION['connection'];
      if(!isset($_SESSION['id'])){
        if(isset($_COOKIE['login-cookie'])){
          $cookie = $_COOKIE['login-cookie'];
          $content = base64_decode ($cookie);
          list($myID, $hashed_password) = explode (':', $content);
    
          $sql = "SELECT heslo FROM uzivatele WHERE id = ?";
          if ($stmt = $conn->prepare($sql)){
            $stmt->bind_param('s', $myID);
            if($stmt->execute()){
              if ($stmt->num_rows > 0) {
                $stmt->bind_result($pass);
                $stmt->fetch();

                if(md5($pass, substr(md5($pass), 1, 9)) == $hashed_password) {
                  $_SESSION['id'] = $row['id'];
                  return true;
                }
              }
            }
            return false;
          }

        }
        return false;
      }
      /*
      if(!isset($_SESSION['id']) && $title == "Přihlášení"){
        header("location: ./index.php");
      }
      */
      return true;  
    }else
      return false;
  }
  
  require_once "../config.inc.php";
  require_once "../functions.inc.php";

  $errors = array();
  
  //$recaptcha = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Ldn6qYUAAAAADtT7BVrfynf4ceDAE1Didhh-QwX&response=' . $_POST['g-recaptcha-response']));
  
  if(ULogged())
    header("location: ../index.html");
  
  if(count($_COOKIE) <= 0) {
    if(isset($_SESSION['cookie_error_count']) && $_SESSION['cookie_error_count']==true){ 
      die ("Povolte v nastavení Cookies! A refreshnete stránku! PS: může se stát že máte cookies povolené tak projistotu kdyžtak také refreshněte stránku!");
    }
      $_SESSION['cookie_error'] = true;
      header("Refresh:0");
  }else{
    $_SESSION['cookie_error'] = false;
  }
  
  $title = "Přihlášení";
  if(isset($_POST['username']) && isset($_POST['pass'])){
    //if($recaptcha->{'success'} == 'true'){
      if ($stmt = $conn->prepare('SELECT id, heslo FROM uzivatele WHERE nick = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
          $stmt->bind_result($id, $password);
          $stmt->fetch();
          // Account exists, now we verify the password.
          // Note: remember to use password_hash in your registration file to store the hashed passwords.
          if (password_verify($_POST['pass'], $password)) {
          // Verification success! User has loggedin!
          // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
          $_SESSION['loggedin'] = TRUE;
          $_SESSION['name'] = $_POST['username'];
          $_SESSION['id'] = $id;
          
          $heslo = $password;
          $salt = substr(md5($heslo), 1, 9);
          $cookie = base64_encode ($id.":". md5 ($heslo, $salt));
        
          if(isset($_POST['longterm'])){
            setcookie("login-cookie", $cookie, 2147483647, "/");
            $_SESSION['cookie'] = $cookie;
            $_SESSION['time'] = (time() + (86400 * 30)); 
            //header("./setcookie.php");
          }else{
            setcookie("login-cookie", $cookie, 0, "/");
            $_SESSION['cookie'] = $cookie;
            $_SESSION['time'] = 0; 
            //header("./setcookie.php");
          }
          header("location: ../index.html");
          } else {
          array_push($errors, "Špatné heslo!");
          }
        } else {
          array_push($errors, "Špatné jméno!");
        }
        $stmt->close();
      }
    /*}else {
      echo('Uživatel není člověk.<br>');
      if ($recaptcha->{'error-codes'}) {
        echo('Při ověřování nastala chyba: ');
        if ($recaptcha->{'error-codes'}  == 'missing-input-secret') {
          echo('Secret kód nebyl serveru předán');
        } elseif ($recaptcha->{'error-codes'}  == 'invalid-input-secret') {
          echo('Secret kód je neplatný');
        } elseif ($recaptcha->{'error-codes'}  == 'missing-input-response') {
          echo('Odpověď klienta nebyla serveru předána');
        } elseif ($recaptcha->{'error-codes'}  == 'invalid-input-response') {
          echo('Odpověď klienta je neplatná');
        }
      }
    }
    */
  }
?>
<!DOCTYPE html>
<html lang="cz">

  <head>
    <title>Přihlášení</title>
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
              <i class="fas fa-leaf"></i>
            </span>

            <span class="login100-form-title p-b-34 p-t-27">
              Přihlášení
            </span>

            <?php if(count($errors) != 0){ ?>
            <div class="text-center p-t-15">
                <?php foreach ($errors as $error) : ?>
                <p class="text" style="color:white;"><?php echo $error; ?></p>
              <?php endforeach ?>
            </div>
            <?php } ?>

            <div class="wrap-input100 validate-input" data-validate="Zadej nick">
              <input class="input100" type="text" name="username" placeholder="Nick">
              <span class="focus-input100" data-placeholder="&#xf207;"></span>
            </div>

            <div class="wrap-input100 validate-input" data-validate="Zadej heslo">
              <input class="input100" type="password" name="pass" placeholder="Heslo">
              <span class="focus-input100" data-placeholder="&#xf191;"></span>
            </div>

            <div class="contact100-form-checkbox">
              <input class="input-checkbox100" id="ckb1" type="checkbox" name="longterm">
              <label class="label-checkbox100" for="ckb1">
                Zapamatovat si mě
              </label>
            </div>

            <div class="container-login100-form-btn">
              <button class="login100-form-btn">
                Přihlásit se
              </button>
            </div>

            <div class="text-center p-t-90">
              <a class="txt1" href="./recover-pass.php">
                Zapomenuté heslo?
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