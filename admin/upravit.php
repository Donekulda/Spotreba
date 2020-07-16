<?php
    session_start();
  require_once "../config.inc.php";
  require_once "../functions.inc.php";
  
  if(!IsLogged($title))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") < 2){
    header("location: ../error.php");
  }

  function CreateIp($Nazev, $Druh, $Vykon, $Jednotka, $ip, $port, $typ){
    global $conn;
    $query = "INSERT INTO ip_adresy (Ip_Nazev, ip, port, Vykon, Jednotka, druh, typ_id, Secret_key_ip) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    if($res = $conn->prepare($query)){
      $res->bind_param("ssidisis", $Nazev, $ip, $port, $Vykon, $Jednotka, $Druh, $typ, $secret_ip); 

      $secret_ip = GenerateKey(73); // Secrret_key pro ip  
    
      if($res->execute()){
        $ip_id = GetId("ip_adresy", "Ip_Nazev", $Nazev);
        return $ip_id;
      }else{
        echo "chyba";
        echo $conn->error;
      //header('location: list.php');
      }        
    }else{
      echo $conn->error; 
    }
  }
    
  $id = (int)$_GET['id']; //Id právě upravujícího profilu
  $key = $_GET['Secret_key']; //Klíč právě upravujího profilu
  $typ = (int)$_GET['Typ']; // Tyo úpravy: 0 = uživatel , 1 = ip
  if($typ == 1){
    $disable = false;
    $title = "Úprava Ip";

    if(!($Data_key = GetVariable($id, "ip_adresy", "Secret_key_ip"))){ //Kontroluje zda dostal Secret_key z database
      echo $conn->error;
    }

    $ip_id = $id;
  }elseif($typ==0){
    $title = "Úprava profilu";

    if(!($Data_key = GetVariable($id, "uzivatele", "Secret_key"))){ //Kontroluje zda dostal Secret_key z database
      echo $conn->error;
    }
  }
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
     
  require_once '../plugins/PHPMailer/src/Exception.php';
  require_once '../plugins/PHPMailer/src/PHPMailer.php';
  require_once '../plugins/PHPMailer/src/SMTP.php';

  if($key == $Data_key && $typ == 0){
    $ip_id = GetVariable($id, "uzivatele", "ip_id");
    $opravneni = GetVariable($id, "uzivatele", "opravneni"); //úrověň oprávnění právě upravujícího profil
  }

  if(isset($ip_id)){
    $faze_id = GetVariable($ip_id, "ip_adresy", "faze_id");
    $wattrouter_id = GetVariable($id, "ip_adresy", "wattrouter_type");
  }else{
    $faze_id = 1;
  }
  
  $opravneni_editor = GetVariable($_SESSION['id'], "uzivatele", "opravneni");//oprávnění editora zvoleného účtu

  if(isset($_POST['edit']) && $key == $Data_key){
    if($typ == 0){
      if(isset($_POST['email'])){
        ChangeString($id, "uzivatele", "email", $_POST['email']);
      }
      if(isset($_POST['tel'])){
        ChangeString($id, "uzivatele", "tel", $_POST['tel']);
      }
      if(isset($_POST['jmeno'])){
        ChangeString($id, "uzivatele", "Jmeno", $_POST['jmeno']);
      }
      if(isset($_POST['prijmeni'])){
        ChangeString($id, "uzivatele", "Prijmeni", $_POST['prijmeni']);
      }
      if(isset($_POST['adresa'])){
        ChangeString($id, "uzivatele", "Adresa", $_POST['adresa']);
      }
      if(isset($_POST['mesto'])){
        ChangeString($id, "uzivatele", "Mesto", $_POST['mesto']);
      }
      if(isset($_POST['PSC'])){
        ChangeString($id, "uzivatele", "PSC", $_POST['PSC']);
      }
      if(isset($_POST['opravneni'])){
        ChangeString($id, "uzivatele", "opravneni", $_POST['opravneni']);
      }
      if(isset($_POST['ip-type'])){
        if((int)$_POST['ip-type'] == 0){
          ChangeString($id, "uzivatele", "ip_id", CreateIp($_POST['nazev'], $_POST['druh'], $_POST['vykon'], $_POST['jednotky'], $_POST['ip'], $_POST['port'], $_POST['typ']));
          $disable = false;
        }
        else{
          $ip_id_change = GetId("ip_adresy", "Ip_Nazev", $_POST['nazev']);
          ChangeString($id, "uzivatele", "ip_id", (($ip_id_change <= 0||$ip_id_change == NULL)?1:$ip_id_change));
          $ip_id = (($ip_id_change <= 0||$ip_id_change == NULL)?1:$ip_id_change);
          $disable = true;
        }
      }else{
        $disable = true;
      }
      header("Location: ./uzivatele.php");
    }elseif($typ == 1){
      if(isset($_POST['nazev'])){
        ChangeString($ip_id, "ip_adresy", "Ip_Nazev", $_POST['nazev']);
      }
      if(isset($_POST['druh'])){
        ChangeString($id, "ip_adresy", "druh", $_POST['druh']);
      } 
      if(isset($_POST['vykon'])){
        ChangeString($id, "ip_adresy", "Vykon", $_POST['vykon']);
      }
      if(isset($_POST['jednotky'])){
        ChangeString($id, "ip_adresy", "Jednotka", $_POST['jednotky']);
      }
      if(isset($_POST['ip'])){
        ChangeString($ip_id, "ip_adresy", "ip", $_POST['ip']);
      } 
      if(isset($_POST['port'])){
        ChangeString($ip_id, "ip_adresy", "port", $_POST['port']);
      }
      if(isset($_POST['faze-type'])){
        ChangeString($ip_id, "ip_adresy", "faze_id", $_POST['faze-type']);
      }
      if(isset($_POST['typ'])){
        ChangeString($id, "ip_adresy", "typ_id", $_POST['typ']);
      }
      if(isset($_POST['nameFaze1'])){
        ChangeString($id, "ip_adresy", "Nazev_Faze1", $_POST['nameFaze1']);
      }
      if(isset($_POST['nameFaze2'])){
        ChangeString($id, "ip_adresy", "Nazev_Faze2", $_POST['nameFaze2']);
      }
      if(isset($_POST['nameFaze3'])){
        ChangeString($id, "ip_adresy", "Nazev_Faze3", $_POST['nameFaze3']);
      }
      if(isset($_POST['wattrouter-type'])){
        ChangeString($id, "ip_adresy", "wattrouter_type", $_POST['wattrouter-type']);
      }
      header("Location: ./zarizeni.php");
    }
  }elseif(!isset($_POST['edit']) && $key == $Data_key){
    if($typ == 0){
      $disable = true;
    }elseif($typ == 1){
      $disable = false;
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta name="description" content="Adminská sekce pro spotreba.solarnivyroba.cz">
    <meta name="author" content="Mikuláš Staněk">
    
    <script type="text/javascript" src="./js/mesta.js"></script>
    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />

    <title>Admin sekce - <?php echo $title; ?></title>
  </head>
  <body <?php if($disable){ echo 'onload="Disable();"'; } ?> >
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script type="text/javascript" src="./js/mesta.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> 
  <div class="templatemo-flex-row">
  <?php 
    require_once "./menu.inc.php";
        
  if($key != $Data_key){ // Porovnává klíče
    echo "<div class = 'error center'>No tak to je blblý kámo,<br />tvůj zadaný klíč je špatný!!!</div>";              
  }else{
    $mocnina = GetVariable($ip_id, "ip_adresy", "Jednotka");
?>
<form method = "post"> 
  <?php 
    if($typ==0){
  ?>
  <div class="row form-group kontrola">
    <div class="col-lg-6 col-md-6 form-group">                   
        <label for="inputNick">Nick: </label>
      <input type="text" class="form-control" name="Nick" id="inputNick" value="<?php echo GetVariable($id, "uzivatele", "nick"); ?>" placeholder="Nick" readOnly> 
    </div>
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputEmail">Email</label>
        <input type="email" class="form-control" name="email" id="inputEmail" value="<?php echo GetVariable($id, "uzivatele", "email") ?>" placeholder="e-mail" pattern="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?" required> 
    </div> 
  </div>

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                   
        <label for="inputTel">Telefon: </label>
      <input type="tel" class="form-control" name="tel" id="inputTel" value="<?php echo GetVariable($id, "uzivatele", "telefon"); ?>" placeholder="Telefon"> 
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputJmeno">Jméno: </label>
        <input type="text" class="form-control" name="jmeno" id="inputJmeno" value="<?php echo GetVariable($id, "uzivatele", "Jmeno"); ?>" placeholder="Jméno" required>                  
    </div>
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputEmail">Příjmení: </label>
        <input type="text" class="form-control" name="prijmeni" id="inputEmail" value="<?php echo GetVariable($id, "uzivatele", "Prijmeni"); ?>" placeholder="Příjmění" required>                  
    </div> 
  </div>   

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">  
      <label for="inputAdresa">Adresa: </label>
      <input type="text" class="form-control" value="<?php echo GetVariable($id, "uzivatele", "Adresa"); ?>" name="adresa" id="inputAdresa" placeholder="Adresa">
    </div> 
  </div>

  <div class="row form-group">
    <div class="col-lg-4 col-md-6 form-group autocomplete"> 
      <label for="inputMesto">Město: </label>
      <input type="text" class="form-control" value="<?php echo GetVariable($id, "uzivatele", "Mesto"); ?>" name="mesto" id="inputMesto" placeholder="Město">
    </div> 
    <div class="col-lg-2 col-md-6 form-group">                   
        <label for="inputPSC">PSČ: </label>
      <input type="zip" class="form-control" value="<?php echo GetVariable($id, "uzivatele", "PSC"); ?>"  name="PSC" id="inputPSC" placeholder="PSČ"> 
    </div>
  </div>
  <div class="row form-group">
    <div class="col-lg-6 form-group">
      <label class="control-label templatemo-block">Druh Ip</label>                    
      <select id="search" class="select-css form-control" name="ip-type">           
      </select>
    </div>
  </div>
  <?php 
    }
  ?>
  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputNazev">Název </label>
        <input type="text" class="form-control" name="nazev" id="inputNazev" value="<?php echo GetVariable($ip_id, "ip_adresy", "Ip_Nazev"); ?>" placeholder="Joudárka">         
    </div> 
  </div> 
  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputDruh">Druh zařízení </label>
        <input type="text" class="form-control" name="druh" id="inputDruh" value="<?php echo GetVariable($ip_id, "ip_adresy", "druh"); ?>" placeholder="Solax...">                  
    </div>
  </div> 

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputIp">Ip měřáku </label>
        <input type="text" class="form-control" name="ip" id="inputIp" value="<?php echo GetVariable($ip_id, "ip_adresy", "ip"); ?>" placeholder="192.168.17.1">         
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
        <label for="inputPort">Port </label>
        <input type="number" class="form-control" name="port" id="inputPort"  value="<?php echo GetVariable($ip_id, "ip_adresy", "port"); ?>">                  
    </div> 
  </div>
  
  <div class="form-group text-left">    
    <button type="button" id="MyIp" onclick="GetMyIp()" title="Dodá stávající Ipv4" class="templatemo-blue-button">Moje Ip</button> 
  </div>

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Typ ip</label>                 
      <select onchange="checkForSelectedType(this)" class="select-css form-control" id="inputTyp" value="<?php echo GetVariable($ip_id, "ip_adresy", "typ_id"); ?>" name="typ">
        <option value="1" selected>Wattmeter</option>
        <option value="2">SolarLog</option>                 
      </select>
    </div>     

    <div class="col-lg-2 col-md-6 form-group" id="typeOfWattrouterDiv"> 
      <label class="control-label templatemo-block">Druh wattrouteru</label>                 
      <select id="search-wattrouters" class="select-css form-control" name="wattrouter-type" value="<?php echo GetVariable($ip_id, "ip_adresy", "wattrouter_type"); ?>">          
        <?php
          $sql="SELECT * FROM wattrouter_types";
          if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_array()) {
              echo "<option value=" . $row['id'] . " " . (($row['id'] == $wattrouter_id)?"selected":"") . ">" . $row['Nazev'] . "</option>";
            }
          }
        ?> 
      </select>
    </div>    

    <div class="col-lg-2 col-md-6 form-group" id="typeOfWattrouterDiv"> 
      <label class="control-label templatemo-block">Druh fází</label>                 
      <select id="search-faze" class="select-css form-control" name="faze-type" value="<?php echo $faze_id; ?>">          
        <?php
          $sql="SELECT * FROM Faze";
          if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_array()) {
              echo "<option value=" . $row['id'] . " ".(($row['id'] == $faze_id)?"selected":"").">" . $row['Nazev'] . "</option>";
            }
          }
        ?> 
      </select>
    </div> 
  </div>  

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputFaze1">Fáze 1 </label>
        <input type="text" class="form-control" name="nameFaze1" id="inputFaze1" value="<?php echo GetVariable($ip_id, "ip_adresy", "Nazev_Faze1"); ?>" placeholder="Nazev 1">         
    </div>

    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputFaze2">Fáze 2 </label>
        <input type="text" class="form-control" name="nameFaze2" id="inputFaze2" value="<?php echo GetVariable($ip_id, "ip_adresy", "Nazev_Faze2"); ?>" placeholder="Nazev 2">         
    </div>

    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputFaze3">Fáze 3 </label>
        <input type="text" class="form-control" name="nameFaze3" id="inputFaze3" value="<?php echo GetVariable($ip_id, "ip_adresy", "Nazev_Faze3"); ?>" placeholder="Nazev 3">         
    </div>
  </div>  

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputVykon">Vykon </label>
        <input type="number" step="0.001" class="form-control" name="vykon" value="<?php echo GetVariable($ip_id, "ip_adresy", "Vykon"); ?>" id="inputVykon" placeholder="15">                  
    </div> 
    <div class="col-lg-1 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Jendotka</label>                 
      <select class="select-css form-control" id="inputJednotka" name="jednotky" value="<?php echo GetVariable($ip_id, "ip_adresy", "Jednotka"); ?>" <?php if($disable){ echo "readOnly='true'"; } ?>>
        <option value="0" <?php echo ($mocnina==0?" selected":""); ?>>Wp</option>
        <option value="3" <?php echo ($mocnina==3?" selected":""); ?>>kWp</option>                 
      </select>
    </div>     
  </div>  

  <?php 
    if($typ==0){
  ?>
  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Oprávnění</label>                 
      <select class="select-css form-control" name="opravneni">
        <option value="1" <?php echo ($opravneni==1?" selected=\"selected\"":""); ?>>Uživatel</option>
        <option value="2" <?php  echo ($opravneni==2?" selected=\"selected\"":""); ?>>Admin</option>
        <?php  if($opravneni>2){?> 
        <option value=3 <?php echo ($opravneni==3?" selected=\"selected\"":""); ?>>Hl.Admin</option>                
        <?php } ?>
      </select>
    </div>     
  </div>  

  <?php 
    }
  ?>
  <div class="form-group text-left">
    <button type="submit" class="templatemo-blue-button" name="edit">Upravit</button>
    <button type="reset" class="templatemo-white-button">Reset</button>
  </div> 

</form>

<script>
  function show1(){
    document.getElementById('ip1').style.display ='flow';
    document.getElementById('ip2').style.display = 'none';
  }
  function show2(){
    document.getElementById('ip1').style.display = 'none';
    document.getElementById('ip2').style.display = 'flow';
  }

  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
    });

    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }

    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
  /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
  }

  function Disable(){
          //Část kodu kde se disablne input
          document.getElementById("inputNazev").readOnly = true;
          document.getElementById("inputDruh").readOnly = true;
          document.getElementById("inputIp").readOnly = true;
          document.getElementById("inputPort").readOnly = true;
          document.getElementById("inputTyp").readOnly = true;
          document.getElementById("search-faze").readOnly = true;
          document.getElementById("search-wattrouters").readOnly = true;
          document.getElementById("inputFaze1").readOnly = true;
          document.getElementById("inputFaze2").readOnly = true;
          document.getElementById("inputFaze3").readOnly = true;
          document.getElementById("inputVykon").readOnly = true;
          document.getElementById("inputJednotka").readOnly = true;
  } 

  function IpChosen(id, secret){
    if (id == 0){
      document.getElementById("inputNazev").value = "";
      document.getElementById("inputDruh").value = "";
      document.getElementById("inputIp").value = "";
      document.getElementById("inputPort").value = 80;
      document.getElementById("inputTyp").value = 1;
      document.getElementById("inputVykon").value = 0;
      document.getElementById("inputJednotka").value = 0;
      document.getElementById("search-wattrouters").value = 1;
      document.getElementById("search-faze").value = 1;
      document.getElementById("inputFaze1").value = "";
      document.getElementById("inputFaze2").value = "";
      document.getElementById("inputFaze3").value = "";

      //Část kodu kde se povolí input
      document.getElementById("inputNazev").readOnly = false;
      document.getElementById("inputDruh").readOnly = false;
      document.getElementById("inputIp").readOnly = false;
      document.getElementById("inputPort").readOnly = false;
      document.getElementById("inputTyp").readOnly = false;
      document.getElementById("inputVykon").readOnly = false;
      document.getElementById("inputJednotka").readOnly = false;
      document.getElementById("search-wattrouters").readOnly = false;
      document.getElementById("search-faze").readOnly = false;
      document.getElementById("inputFaze1").readOnly = false;
      document.getElementById("inputFaze2").readOnly = false;
      document.getElementById("inputFaze3").readOnly = false;
    }else{
      if (window.XMLHttpRequest) {
        // code for modern browsers
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for old IE browsers
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } 
      
      xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
          var myObj = JSON.parse(this.responseText);
            document.getElementById("inputNazev").value = myObj.Nazev;
            document.getElementById("inputDruh").value = myObj.Druh;
            document.getElementById("inputIp").value = myObj.Ip;
            document.getElementById("inputPort").value = myObj.Port;
            document.getElementById("inputTyp").value = myObj.Typ;
            document.getElementById("inputVykon").value = myObj.Vykon;
            document.getElementById("inputJednotka").value = myObj.Jednotka;
            document.getElementById("search-wattrouters").value = myObj.Wattrouter;
            document.getElementById("search-faze").value = myObj.faze;
            document.getElementById("inputFaze1").value = myObj.Name_faze1;
            document.getElementById("inputFaze2").value = myObj.Name_faze2;
            document.getElementById("inputFaze3").value = myObj.Name_faze3;

            //Část kodu kde se disablne input
            document.getElementById("inputNazev").readOnly = true;
            document.getElementById("inputDruh").readOnly = true;
            document.getElementById("inputIp").readOnly = true;
            document.getElementById("inputPort").readOnly = true;
            document.getElementById("inputTyp").readOnly = true;
            document.getElementById("inputVykon").readOnly = true;
            document.getElementById("inputJednotka").readOnly = true;
            document.getElementById("search-wattrouters").readOnly = true;
            document.getElementById("search-faze").readOnly = true;
            document.getElementById("inputFaze1").readOnly = true;
            document.getElementById("inputFaze2").readOnly = true;
            document.getElementById("inputFaze3").readOnly = true;
        }
      };
      xmlhttp.open("GET", "./ajax/GetIpInfo.php?id=" + id + "&secret=" + secret, true);
      xmlhttp.send();
    }
  }
  $(document).ready(function() {

    $("#search").select2({
      data:
    <?php
      $sql = "SELECT id, ip, port, Ip_Nazev as Nazev, Secret_key_ip as SecretKEY, Deactivated FROM ip_adresy";
        
      $query = $conn->query($sql);
  
      if ($query->num_rows > 0) {
        // output data of each row
        $i = 1;
        
        echo '[{';
        echo 'id: 0,';
        echo 'text: "Nová Ip",';
        echo 'ip_id: 0,';
        echo 'Secret: "New",';
        echo 'selected: false';
        echo '}';
        while($row = $query->fetch_assoc()) {
          echo ', {';
          echo 'id: '.$i.',';
          echo 'text: "'.$row['Nazev'].' ('.$row['ip'].':'.$row['port'].')'.($row['Deactivated']!=0?" --------------------------------( Ip Deaktivována )--------------------------------":"").'",';
          echo 'ip_id: '.$row['id'].',';
          if($row['id'] == $ip_id)
            echo 'selected: true,';
          echo 'Secret: "'.$row['SecretKEY'].'"';
          echo '}';
          $i++;
        }
        echo ']';
      }
    ?>  
    });

    $("#search").on('select2:select', function (e) {
      var data = e.params.data;
      console.log(data);
      IpChosen(data.ip_id, data.Secret);
    });
  });
  
  function GetMyIp(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("inputIp").value = this.responseText;
        }
    };
    xmlhttp.open("GET", "./ajax/GetMyIp.php", true);
    xmlhttp.send();
  }
  
  function checkForSelectedType(selectVal){
    if(selectVal.value == 1){
      //Zde se mi schová tlačítko 'Moje Ip'
      document.getElementById("typeOfWattrouterDiv").style.display = "block";
      document.getElementById("typeOfWattrouterDiv").disable = false;
    }else{
      //Zde se mi schová tlačítko 'Moje Ip'
      document.getElementById("typeOfWattrouterDiv").style.display = "none";
      document.getElementById("typeOfWattrouterDiv").disable = true;
    }
  }

  autocomplete(document.getElementById("inputMesto"), Mesta);
</script>
<?php
  }  
  require_once "./footer.inc.php";
?>