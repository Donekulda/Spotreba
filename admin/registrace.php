<?php
  $title = "Registrace uživatele";
  require_once "./header.inc.php";
  
  $nick = "";
  $telefon = "";
  $email = "";
  $jmeno = "";
  $prijmeni = "";
  $adresa = "";
  $mesto = "";
  $nazev = "";
  $druh = "";
  $ip = "";
  $port = $default_port;
  $opravneni = 0;
  $PSC = 0;
  $wattroute_typ = 0;
  $errors = array();
  $infos = array();
  $ip_exists = false;
  
  function GetLenght($table){
    if(isset($_SESSION['connection'])){
      $sql = "SELECT * FROM ".$table;
      $result = $_SESSION['connection']->query($sql);
      
      return $result->num_rows;
    }
  }
  
  if (isset($_POST['register'])) {
    // receive all input values from the form
    $nick = mysqli_real_escape_string($conn, $_POST['nick']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefon = mysqli_real_escape_string($conn, $_POST['tel']);
    $jmeno = mysqli_real_escape_string($conn, $_POST['jmeno']);
    $prijmeni = mysqli_real_escape_string($conn, $_POST['prijmeni']);
    $adresa = mysqli_real_escape_string($conn, $_POST['adresa']);
    $mesto = mysqli_real_escape_string($conn, $_POST['mesto']);
    $nazev = mysqli_real_escape_string($conn, $_POST['nazev']);
    $druh = mysqli_real_escape_string($conn, $_POST['druh']);
    $ip = mysqli_real_escape_string($conn, $_POST['ip']);
    $ip_type = mysqli_real_escape_string($conn, $_POST['ip-type']);;
    $password = mysqli_real_escape_string($conn, GenerateKey(12));
    $heslo = $password;
    if($ip_type > 0)
      $ip_exists = true;

    // za každou nevyplněnou kolonku přidáme si do pole errors error
    if (empty($nick)) { array_push($errors, "Nick je potřeba"); }
    if (empty($email)) { array_push($errors, "e-mail je potřeba"); }
    if (empty($jmeno)) { array_push($errors, "Jméno je potřeba"); }
    if (empty($prijmeni)) { array_push($errors, "Příjmení je potřeba"); }
    if(!$ip_exists)
      if (empty($ip)) { array_push($errors, "Ip routru je potřeba"); }
    //if (empty($Vykon)) { array_push($errors, "Vykon je potřeba zadat"); } 
      
    if (DoesExist("uzivatele", "nick", $nick)) {
      array_push($errors, "Nick už existuje!");
    }

    if (DoesExist("uzivatele", "email", $email)) {
      array_push($errors, "email už existuje!");
    }

    if(!$ip_exists){
      if(DoesExist("ip_adresy", "ip", $ip)){
        if(GetVariable(GetId("ip_adresy", "ip", $ip), "ip_adresy", "port") == $port){
          array_push($errors, "Ip s tímto portem již je zadaná, prosím zvolte možnost zvolení 'existující ip adresa'");
        }elseif (DoesExist("ip_adresy", "Ip_Nazev", $nazev)){
          array_push($errors, "Ip s tímto názvem již existuje!");
        }
      }elseif (DoesExist("ip_adresy", "Ip_Nazev", $nazev)) {
        array_push($errors, "Ip s tímto názvem již existuje!");
      }  
    }

    if (count($errors) == 0) {
      $password = password_hash($heslo,PASSWORD_DEFAULT); 

      $query = "INSERT INTO uzivatele (nick, heslo, email, telefon, Jmeno, Prijmeni, Adresa, Mesto, PSC, ip_id, opravneni, Secret_key) 
      VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      if($res = $conn->prepare($query)){
        $res->bind_param("ssssssssiiis",$nick, $password, $email, $telefon, $jmeno, $prijmeni, $adresa, $mesto, $PSC, $ip_id, $opravneni, $secret);
                       
        $jednotky = $_POST['jednotky'];
        $port = $_POST['port']; 
        $Vykon = $_POST['vykon'];
        $opravneni = $_POST['opravneni'];
        $PSC = $_POST['PSC'];
        $faze_id = $_POST['faze-type'];
        $secret = GenerateKey(73);
        
        if(!$ip_exists){
          $query = "INSERT INTO ip_adresy (Ip_Nazev, Nazev_Faze1, Nazev_Faze2, Nazev_Faze3, ip, port, Vykon, Jednotka, wattrouter_type, Faze_id, druh, typ_id, Secret_key_ip) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
          if($res_2 = $conn->prepare($query)){
            $res_2->bind_param("sssssidiiisis", $nazev, $Nazev_faze1, $Nazev_faze2, $Nazev_faze3, $ip, $port, $Vykon, $jednotky, $wattrouter_type, $faze_id, $druh, $typ, $secret_ip); 

            $Nazev_faze1 = $_POST['nameFaze1'];
            $Nazev_faze2 = $_POST['nameFaze2'];
            $Nazev_faze3 = $_POST['nameFaze3'];
            $secret_ip = GenerateKey(73); // Secrret_key pro ip
            $typ = $_POST['typ'];               
            if($typ == 1){
              $wattroute_type = $_POST['wattrouter-type'];
            }else{
              $wattrouter_type = null;
            }
          
            if($res_2->execute()){
              $ip_id = GetId("ip_adresy", "Ip_Nazev", $nazev);
              echo $ip_id;

              if($res->execute()){        
                array_push($infos, "Uživatel ".$nick." byl vytvořen");
              
                System_mail($email, $jmeno, $prijmeni, 1, "Přihlašovací údaje", ["%nick"=>$nick, "%heslo"=>$heslo, "%jmeno"=>$jmeno, "%prijmeni"=>$prijmeni]);    
                System_mail($system_mail, $Org_name, "", 7, "Admin->Nový uživatel", ["%nick"=>$nick, "%heslo"=>$heslo, "%jmeno"=>$jmeno, "%prijmeni"=>$prijmeni]);     
            
                //mail($email, "Přihlašovací údaje", $msg, $header);
              }else{
                echo "chyba";
                echo $conn->error;
              //header('location: list.php');
              }
            }else{
              echo "chyba";
              echo $conn->error;
            //header('location: list.php');
            }        
          }else{
            echo $conn->error; 
          }
        }else{
          $ip_id = GetId("ip_adresy", "Ip_Nazev", $_POST['nazev']);
          if($res->execute()){
            array_push($infos, "Uživatel ".$nick." byl vytvořen");
            
            System_mail($email, $jmeno, $prijmeni, 1, "Přihlašovací údaje", ["%nick"=>$nick, "%heslo"=>$heslo, "%jmeno"=>$jmeno, "%prijmeni"=>$prijmeni]); 
            System_mail($system_mail, $Org_name, "", 7, "Admin->Nový uživatel", ["%nick"=>$nick, "%heslo"=>$heslo, "%jmeno"=>$jmeno, "%prijmeni"=>$prijmeni]);          
          
            //mail($email, "Přihlašovací údaje", $msg, $header);
          }else{
            echo "chyba";
            echo $conn->error;
          //header('location: list.php');
          }
        }
      }else{
        echo $conn->error; 
      }
    }  
  } 
?>
<script type="text/javascript" src="./js/mesta.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> 
<div class="templatemo-flex-row flex-content-row">
<?php  if (count($errors) > 0) { ?>
  <div class="templatemo-content-widget orange-bg col-2">
    <i class="fa fa-times"></i>
    <h2 class="text-uppercase">Error</h2>
    <h3 class="text-uppercase margin-bottom-10">výpis errorů</h3>
    <?php foreach ($errors as $error) : ?>
  	  <p><?php echo $error; ?></p>
  	<?php endforeach ?>
  </div>
  	
<?php  } 
  if (count($infos) > 0) { ?>
  <div class="templatemo-content-widget blue-bg col-2">
    <i class="fa fa-times"></i>
    <h2 class="text-uppercase">Info</h2>
    <?php foreach ($infos as $info) : ?>
  	  <p><?php echo $info; ?></p>
  	<?php endforeach ?>
  </div>
  	
  <?php } ?>
</div>

<form method = "post">

  <div class="row form-group kontrola">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputNick">Nick:</label>
        <input type="text" class="form-control" name="nick" id="inputNick" placeholder="Nick" required>                  
    </div>
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputEmail">Email</label>
        <input type="email" class="form-control" name="email" id="inputEmail" placeholder="e-mail" pattern="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?" required> 
    </div> 
  </div>

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                   
        <label for="inputTel">Telefon: </label>
      <input type="tel" class="form-control" name="tel" id="inputTel" placeholder="Telefon"> 
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputJmeno">Jméno: </label>
        <input type="text" class="form-control" name="jmeno" id="inputJmeno" placeholder="Jméno" required>                  
    </div>
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputEmail">Příjmení: </label>
        <input type="text" class="form-control" name="prijmeni" id="inputEmail" placeholder="Příjmění" required>                  
    </div> 
  </div>    

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">  
      <label for="inputAdresa">Adresa: </label>
      <input type="text" class="form-control" name="adresa" id="inputAdresa" placeholder="Adresa">
    </div> 
  </div>

  <div class="row form-group">
    <div class="col-lg-4 col-md-6 form-group autocomplete"> 
      <label for="inputMesto">Město: </label>
      <input type="text" class="form-control" name="mesto" id="inputMesto" placeholder="Město">
    </div> 
    <div class="col-lg-2 col-md-6 form-group">                   
        <label for="inputPSC">PSČ: </label>
      <input type="zip" class="form-control" name="PSC" id="inputPSC" placeholder="PSČ"> 
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-6 form-group">
      <label class="control-label templatemo-block">Druh Ip</label>                    
      <select id="search" class="select-css form-control" name="ip-type">           
      </select>
    </div>
  </div>
  
  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputNazev">Název </label>
        <input type="text" class="form-control" name="nazev" id="inputNazev" placeholder="Joudárka">         
    </div> 
  </div> 

  <div class="row form-group">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputDruh">Druh zařízení </label>
        <input type="text" class="form-control" name="druh" id="inputDruh" placeholder="Solax...">                  
    </div>
  </div> 

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputIp">Ip měřáku </label>
        <input type="text" class="form-control" name="ip" id="inputIp" placeholder="192.168.17.1">         
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
        <label for="inputPort">Port </label>
        <input type="number" class="form-control" name="port" id="inputPort" value=<?php echo $default_port; ?>>                  
    </div>
  </div>
  
  <div class="form-group text-left" id="my_ip_button_div">    
    <button type="button" id="MyIp" onclick="GetMyIp()" title="Dodá stávající Ipv4" class="templatemo-blue-button">Moje Ip</button> 
  </div>
  
  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Typ ip</label>                 
      <select onchange="checkForSelectedType(this)" class="select-css form-control" id="inputTyp" name="typ"> =>
        <option value="1" selected>Wattmeter</option>
        <option value="2">SolarLog</option>                 
      </select>
    </div>   

    <div class="col-lg-2 col-md-6 form-group" id="typeOfWattrouterDiv"> 
      <label class="control-label templatemo-block">Druh wattrouteru</label>                 
      <select id="search-wattrouters" class="select-css form-control" name="wattrouter-type">          
        <?php
          $sql="SELECT * FROM wattrouter_types";
          if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_array()) {
              echo "<option value=" . $row['id'] . ">" . $row['Nazev'] . "</option>";
            }
          }
        ?> 
      </select>
    </div> 

    <div class="col-lg-2 col-md-6 form-group" id="typeOfWattrouterDiv"> 
      <label class="control-label templatemo-block">Druh fází</label>                 
      <select id="search-faze" class="select-css form-control" name="faze-type">          
        <?php
          $sql="SELECT * FROM Faze";
          if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_array()) {
              echo "<option value=" . $row['id'] . ">" . $row['Nazev'] . "</option>";
            }
          }
        ?> 
      </select>
    </div>
  </div> 

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputFaze1">Fáze 1 </label>
        <input type="text" class="form-control" name="nameFaze1" id="inputFaze1" placeholder="Nazev 1">         
    </div>

    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputFaze2">Fáze 2 </label>
        <input type="text" class="form-control" name="nameFaze2" id="inputFaze2" placeholder="Nazev 2">         
    </div>

    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputFaze3">Fáze 3 </label>
        <input type="text" class="form-control" name="nameFaze3" id="inputFaze3" placeholder="Nazev 3">         
    </div>
  </div>      

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputVykon">Vykon </label>
        <input type="number" step="0.001" class="form-control" name="vykon" id="inputVykon" placeholder="15,0">                  
    </div> 
    <div class="col-lg-1 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Jendotka</label>                 
      <select class="select-css form-control" id="inputJednotka" name="jednotky">
        <option value="0" selected>Wp</option>
        <option value="3">KWp</option>                 
      </select>
    </div>     
  </div>  

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Oprávnění</label>                 
      <select class="select-css form-control" name="opravneni">
        <option value="1" selected>Uživatel</option>
        <option value="2">Admin</option>                 
      </select>
    </div>     
  </div>  
  <div class="form-group text-left">
    <button type="submit" class="templatemo-blue-button" name="register">Registrovat</button>
    <button type="reset" class="templatemo-white-button">Reset</button>
  </div> 
</form>

<script>
      
    document.getElementById("inputNazev").required = true;
    document.getElementById("inputIp").required = true;
    document.getElementById("inputPort").required = true;
    document.getElementById("inputTyp").required = true;
    document.getElementById("inputVykon").required = true;
    document.getElementById("inputJednotka").required = true;
    document.getElementById("search-wattrouters").required = true;
    document.getElementById("search-faze").required = true;

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
      
      document.getElementById("inputNazev").required = true;
      document.getElementById("inputIp").required = true;
      document.getElementById("inputPort").required = true;
      document.getElementById("inputTyp").required = true;
      document.getElementById("inputVykon").required = true;
      document.getElementById("inputJednotka").required = true;
      document.getElementById("search-wattrouters").required = true;
      document.getElementById("search-faze").required = true;

      document.getElementById("my_ip_button_div").style.display = "block";
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
      
          //Část kde se vypne požadování vyplnění
          document.getElementById("inputNazev").required = false;
          document.getElementById("inputIp").required = false;
          document.getElementById("inputPort").required = false;
          document.getElementById("inputTyp").required = false;
          document.getElementById("inputVykon").required = false;
          document.getElementById("inputJednotka").required = false;
          document.getElementById("search-wattrouters").required = false;
          document.getElementById("search-faze").required = false;

          //Zde se mi schová tlačítko 'Moje Ip'
          document.getElementById("my_ip_button_div").style.display = "none";
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
        echo 'selected: true';
        echo '}';
        while($row = $query->fetch_assoc()) {
          echo ', {';
          echo 'id: '.$i.',';
          echo 'text: "'.$row['Nazev'].' ('.$row['ip'].':'.$row['port'].')'.($row['Deactivated']!=0?" --------------------------------( Ip Deaktivována )--------------------------------":"").'",';
          echo 'ip_id: '.$row['id'].',';
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
          if(document.getElementById("inputIp").readOnly == false){
            document.getElementById("inputIp").value = this.responseText;
          }
        }
    };
    xmlhttp.open("GET", "./ajax/GetMyIp.php", true);
    xmlhttp.send();
  }

  function checkForSelectedType(selectVal){
    if(selectVal.value == 1){
      //Zde se mi schová tlačítko 'Moje Ip'
      document.getElementById("typeOfWattrouterDiv").style.display = "block";
    }else{
      //Zde se mi schová tlačítko 'Moje Ip'
      document.getElementById("typeOfWattrouterDiv").style.display = "none";
    }
  }
  
  autocomplete(document.getElementById("inputMesto"), Mesta);
</script>

<?php
  require_once "./footer.inc.php";
?>