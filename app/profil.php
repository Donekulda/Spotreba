<?php
    session_start();
    error_reporting(-1);
    ini_set('display_errors', 'On');
    $title = "Profil";
     
    require_once "../config.inc.php";
    require_once "../functions.inc.php"; 
    
    if(count($_COOKIE) <= 0) {
      if(isset($_SESSION['cookie_error_count']) && $_SESSION['cookie_error_count']==true){ 
        die ("Povolte v nastavení Cookies! A refreshnete stránku! PS: může se stát že máte cookies povolené tak projistotu kdyžtak také refreshněte stránku!");
      }
      $_SESSION['cookie_error'] = true;
      header("Refresh:0");
    }else{
      $_SESSION['cookie_error'] = false;
    }
     
    if(!IsLogged($title)){
      header("location: ./error.php");
    }

    if($_SESSION['opravneni'] <= 0 or !isset($_SESSION['id']) or $_SESSION['opravneni'] == '' or !isset($_SESSION['opravneni'])){
      header("location: ./index.php");
    }

    $sql = "SELECT nick, heslo, email, telefon, Jmeno, Prijmeni, Adresa, PSC, Mesto, ip_id, opravneni FROM uzivatele WHERE id=".$_SESSION['id'];
    if($query = $conn->query($sql)){
      while($row = $query->fetch_assoc()){
        $opravneni = $row["opravneni"];
        $nick = $row["nick"];
        $hash_heslo = $row["heslo"];
        $Jmeno = $row["Jmeno"];
        $Prijmeni = $row["Prijmeni"];
        $email = $row["email"];
        $tel = $row["telefon"];
        $Adresa = $row["Adresa"];
        $PSC = $row["PSC"];
        $Mesto = $row["Mesto"];
        $ip_id = $row["ip_id"];
      }
    }else{
      echo $conn->error;
    }

    $ip = GetVariable($ip_id, "ip_adresy", "ip");
    $port = GetVariable($ip_id, "ip_adresy", "port");
    $errors = array();
    $warnings = array();
    $success = array();
    $info = array();

    if(isset($_POST['zmena'])){
      $heslo = "";
      $nove_heslo = "";
      $nove_heslo_znovu = "";

      if(isset($_POST['stare-heslo']) && $_POST['stare-heslo'] != "")
        $heslo = $_POST['stare-heslo'];
      else
        array_push($errors, "Zadejte své stávající heslo do kolonky 'Staré heslo'!");
      
      if(isset($_POST['nove-heslo']) && $_POST['nove-heslo'] != "")
        $nove_heslo = $_POST['nove-heslo'];
      else
        array_push($errors, "Zadejte vámi zvolenné nové heslo do kolonky 'Nové heslo'!");
      
      if(isset($_POST['nove-heslo-znovu']) && $_POST['nove-heslo-znovu'] != "")
        $nove_heslo_znovu = $_POST['nove-heslo-znovu'];
      else
        array_push($errors, "Zopakujte zadání vámi zvolenného nového hesla do kolonky 'Nové heslo znovu'!");
      
      if(sizeof($errors) == 0){
        if($nove_heslo != $nove_heslo_znovu)
          array_push($errors, "Nová hesla nejsou stejná!");

        if(strlen($nove_heslo) < 8)
          array_push($warnings, "Nedoporučuje se míti heslo kratší 8 písmen!");
        
        if(sizeof($errors) == 0){
          if(password_verify($heslo, $hash_heslo)){
            $password = password_hash($nove_heslo,PASSWORD_DEFAULT);
            ChangeString($_SESSION['id'], "uzivatele", "heslo", $password);
            array_push($success, "Tvé heslo bylo úspěšně změněno!");
            if(isset($_COOKIE["login-cookie"])){
              $salt = substr(md5($password), 1, 9);
              $cookie = base64_encode ($_SESSION['id'].":". md5 ($password, $salt));
              setcookie("login-cookie", $cookie, 2147483647, "/");
            }
          }else{
            array_push($errors, "Zadanné 'staré' heslo není validní!");
          }
        }
      }
    }

    if(isset($_POST['tel'])){
      if($_POST['tel'] != $tel){
        ChangeString($_SESSION['id'], "uzivatele", "telefon", $_POST['tel']);
        array_push($success, "Vaše telefoní číslo bylo změněno z ".$tel." na ".$_POST['tel']);
        $tel = $_POST['tel'];
      }

      if(isset($_POST['firstname']) && $_POST['firstname'] != $Jmeno){
        ChangeString($_SESSION['id'], "uzivatele", "Jmeno", $_POST['firstname']);
        array_push($success, "Vaše Jméno bylo změněno z ".$Jmeno." na ".$_POST['firstname']);
        $Jmeno = $_POST['firstname'];
      }

      if(isset($_POST['lastname']) && $_POST['lastname'] != $Prijmeni){
        ChangeString($_SESSION['id'], "uzivatele", "Prijmeni", $_POST['lastname']);
        array_push($success, "Vaše Příjmení bylo změněno z ".$Prijmeni." na ".$_POST['lastname']);
        $Prijmeni = $_POST['lastname'];
      }
    }
?>
<!DOCTYPE html>
<html lang="cz">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Facebook -->
    <meta property="og:url" content="http://spotreba.solarnivyroba.cz">
    <meta property="og:title" content="Aeko - spotřeba">
    <meta property="og:description" content="Grafy zobrazující stav a výrobu připojených elektráren a výsledků z elektroměrů.">

    <!-- Preview image -->
    <meta property="og:image" content="../img/preview_image.png">
    <meta property="og:image:secure_url" content="../img/preview_image.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Webová aplikace společnosti Aeko s.r.o. pro náhled na spotřebu a výrobu fotovoltaických elektráren a objektů na nich připojených.">
    <meta name="author" content="Mikuláš Staněk">

    <!-- vendor css -->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-toggles/toggles-full.css" rel="stylesheet">
    <link href="../lib/rickshaw/rickshaw.min.css" rel="stylesheet">
    <link href="../lib/select2/css/select2.min.css" rel="stylesheet">

    <!-- Amanda CSS -->
    <link rel="stylesheet" href="../css/amanda.min.css">
    
    <script src="../lib/jquery/jquery.js"></script>

  <?php if(!isset($subtitle)){ ?>
    <title><?php echo $title; ?></title>
  <?php }else{ ?>
    <title><?php echo $title." - ".$subtitle; ?></title>
  <?php } ?>
  
  </head>
  <body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <?php
      include_once "./topbar.inc.php";
      include_once "./menu.inc.php";
    ?>

    <div class="am-mainpanel">
      <div class="am-pagetitle">
          <h5 class="am-title">Profil</h5>
          <!-- search-bar -->
      </div>
      <!-- am-pagetitle -->

      <div class="am-pagebody">
        <script>
          function checkPassInput(){
            /*
            oldPass = document.getElementById("oldPassInput").value;
            newPass = document.getElementById("newPassInput").value;
            newPassAgain = document.getElementById("newPassAgainInput").value;
            
            if (oldPass == ''){
              document.getElementById("changePassBtn").disabled = true;
              document.getElementById("passErrorOut").innerHTML = "<br><b>Musí být všechna pole vyplněná!</b>";
              document.getElementById("passErrorOut").style.color = "red";
            }else if (newPass == ''){
              document.getElementById("changePassBtn").disabled = true;
              document.getElementById("passErrorOut").innerHTML = "<br><b>Musí být všechna pole vyplněná!</b>";
              document.getElementById("passErrorOut").style.color = "red";
            }else if (newPassAgain == ''){
              document.getElementById("changePassBtn").disabled = true;
              document.getElementById("passErrorOut").innerHTML = "<br><b>Musí být všechna pole vyplněná!</b>";
              document.getElementById("passErrorOut").style.color = "red";
            }else{
              if(newPass == newPassAgain){
                document.getElementById("changePassBtn").disabled = false;
                document.getElementById("passErrorOut").innerHTML = "";
              }else{
                document.getElementById("changePassBtn").disabled = true;
                document.getElementById("passErrorOut").innerHTML = "<br><b>Hesla se neshodují!</b>";
                document.getElementById("passErrorOut").style.color = "red";
              }
            }
            setTimeout((checkPassInput()), 100);
            */
          }
        </script>
        <?php
          if(sizeof($warnings) > 0){
            foreach($warnings as $value){
              printWarning($value);
            }
          }

          if(sizeof($errors) > 0){
            foreach($errors as $value){
              printError($value);
            }
          }

          if(sizeof($success) > 0){
            foreach($success as $value){
              printSuccess($value);
            }
          }

          if(sizeof($info) > 0){
            foreach($info as $value){
              printInfo($value);
            }
          }
        ?>
        <div class="row row-sm">
          <div class="col-xl-4">
            <div class="card pd-20 pd-sm-20 form-layout form-layout-4">

              <h6 class="card-body-title">Profil</h6>

              <img style="width: 75%; max-width: 250px; text-align: center; margin-left: auto; margin-right: auto;" src="<?php echo $adresa;?>vzhled/obrazky/profil/<?php echo $opravneni.".png";?>">
              
              <hr>
              <form id="passForm" method="post">
                <h6 class="card-body-title">Změna hesla</h6>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Staré heslo: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input id="oldPassInput" onchange="checkPassInput()" type="password" class="form-control" name="stare-heslo" placeholder="Zadej stávající heslo" required>
                  </div>
                </div><!-- row -->

                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Nové heslo: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input id="newPassInput" onchange="checkPassInput()" type="password" class="form-control" name="nove-heslo" placeholder="Zadej nové heslo" required>
                  </div>
                </div>

                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Nové heslo znovu: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input id="newPassAgainInput" onchange="checkPassInput()" type="password" class="form-control" name="nove-heslo-znovu" placeholder="Zadej nové heslo znovu" required>
                  </div>
                </div>

                <p id="passErrorOut" class="mg-b-20 mg-sm-b-30"></p>
                <div class="row row-xs mg-t-30">

                  <div class="col-sm-6 mg-l-auto mg-r-0">
                    <div class="form-layout-footer">
                      <button id="changePassBtn" onclick="ZmenaHesla()" name="zmena" class="btn btn-info mg-r-5"><i class="fa fa-send mg-r-10"></i>Změnit</button>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                    </div><!-- form-layout-footer -->
                  </div><!-- col-8 -->
                </div>
              </form>
            </div><!-- card -->
          </div><!-- col-4 -->

          <div class="col-xl-8">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-4">
              <div class="form-layout">
                <form id="profilForm" name="profilForm" method="post">
                  <h6 class="card-body-title">Osobní info</h6>

                  <div class="row mg-b-24">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label">Nick:</label>
                        <input class="form-control" type="text" name="nick" value="<?php echo $nick;?>" placeholder="Nen nic zadáno" disabled>
                      </div>
                    </div><!-- col-4 -->
                  </div><!-- row -->

                  <div class="row mg-b-24">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label">Jméno:</label>
                        <input id="JmenoInput" class="form-control" type="text" name="firstname" value="<?php echo $Jmeno;?>" placeholder="John" readonly>
                      </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label">Příjmení:</label>
                        <input id="PrijmeniInput" class="form-control" type="text" name="lastname" value="<?php echo $Prijmeni;?>" placeholder="Doe" readonly>
                      </div>
                    </div><!-- col-4 -->
                  </div><!-- row -->

                  <div class="row mg-b-24">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label">Email:</label>
                        <input class="form-control" type="text" name="email" value="<?php echo $email;?>" placeholder="john.doe@email.com" readonly>
                      </div>
                    </div><!-- col-4 -->
                  </div><!-- row -->

                  <div class="row mg-b-24">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label">Tel:</label>
                        <input id="TelInput" class="form-control" type="tel" name="tel" value="<?php echo $tel;?>" placeholder="Není nic zadáno" readonly>
                      </div>
                    </div><!-- col-4 -->
                  </div><!-- row -->

                  <br>

                  <h6 class="card-body-title">Wattrouter / SolarLog</h6>
                  <hr>
                  <div class="row mg-b-24">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label">Ip / DynDNS:</label>
                        <input class="form-control" type="text" value="<?php echo $ip;?>" placeholder="John" readonly>
                      </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-2">
                      <div class="form-group">
                        <label class="form-control-label">Port:</label>
                        <input class="form-control" type="text" value="<?php echo $port;?>" placeholder="Doe" readonly>
                      </div>
                    </div><!-- col-4 -->
                  </div><!-- row -->

                  <br>

                  <h6 class="card-body-title">Bydliště</h6>
                  <hr>
                  <div class="row mg-b-25">
                    <div class="col-lg-4">
                      <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Adresa:</label>
                        <input class="form-control" type="text" name="address" value="<?php echo $Adresa;?>" placeholder="Adámkova 1" readonly>
                      </div>
                    </div><!-- col-8 -->
                    <div class="col-lg-2">
                      <div class="form-group">
                        <label class="form-control-label">PSC:</label>
                        <input class="form-control" type="text" name="zip" value="<?php echo $PSC; ?>" placeholder="666 66" readonly>
                      </div>
                    </div><!-- col-4 -->
                    <div class="col-lg-4">
                      <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Město:</label>
                        <input class="form-control" type="text" name="town" value="<?php echo $Mesto; ?>" placeholder="Brno" readonly>
                      </div>
                    </div><!-- col-4 -->
                  </div><!-- row -->
                </form>
                
                <div class="form-layout-footer mg-t-30">
                  <button id="UpravitBtn" onclick="ShowUpravitProfil(true)" class="btn btn-info mg-r-5">Upravit</button>
                  <button id="PotvrditUpravuBtn" onclick="UpravitProfil()" style="display: none;" class="btn btn-info mg-r-5"><i class="fa fa-send mg-r-10"></i>Pozměnit</button>
                  <button id="ZrusitBtn" onclick="ShowUpravitProfil(false)" style="display: none;" class="btn btn-secondary">Zrušit</button>
                </div><!-- form-layout-footer -->
              </div><!-- form-layout -->
            </div><!-- card -->
          </div><!-- col -->
        </div><!-- row -->

    <script>
      function ZmenaHesla(){
        document.getElementById("passForm").submit();
      }

      function ShowUpravitProfil(bUpravit){
        if(bUpravit){
          document.getElementById("UpravitBtn").style.display = "none";
          document.getElementById("PotvrditUpravuBtn").style.display = "inline";
          document.getElementById("ZrusitBtn").style.display = "inline";

          document.getElementById("TelInput").readOnly = false;
          document.getElementById("JmenoInput").readOnly = false;
          document.getElementById("PrijmeniInput").readOnly = false;
        }else{
          document.getElementById("UpravitBtn").style.display = "inline";
          document.getElementById("PotvrditUpravuBtn").style.display = "none";
          document.getElementById("ZrusitBtn").style.display = "none";

          document.getElementById("TelInput").value = <?php echo $tel; ?>;
          document.getElementById("TelInput").readOnly = true;

          document.getElementById("JmenoInput").value = <?php echo $Jmeno; ?>;
          document.getElementById("JmenoInput").readOnly = true;

          document.getElementById("PrijmeniInput").value = <?php echo $Prijmeni; ?>;
          document.getElementById("PrijmeniInput").readOnly = true;
        }
      }

      function UpravitProfil(){
        document.getElementById("profilForm").submit();
      }
    </script>
  <?php include_once "./footer.inc.php"; ?>