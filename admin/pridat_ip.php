<?php
  $title = "Registrace ip adresy";
  require_once "./header.inc.php";
  
  $nazev = "";
  $druh = "";
  $ip = "";
  $Nazev_faze1 = "";
  $Nazev_faze2 = "";
  $Nazev_faze3 = "";
  $port = $default_port;
  $Vykon = 0.0;
  $faze_id = 0;
  $wattrouter_type = 0;
  $jednotky = 0;
  $typ = 1;
  $errors = array();
  
  function GetLenght($table){
    if(isset($_SESSION['connection'])){
      $sql = "SELECT * FROM ".$table;
      $result = $_SESSION['connection']->query($sql);
      
      return $result->num_rows;
    }
  }
  
  if (isset($_POST['register'])) {
    // receive all input values from the form
    $nazev = mysqli_real_escape_string($conn, $_POST['nazev']);
    $druh = mysqli_real_escape_string($conn, $_POST['druh']);
    $ip = mysqli_real_escape_string($conn, $_POST['ip']);

    // za každou nevyplněnou kolonku přidáme si do pole errors error
    if (empty($nazev)) { array_push($errors, "Název je potřeba vyplnit"); }
    if (empty($druh)) { array_push($errors, "Druh je potřeba vyplnit"); }
    if (empty($ip)) { array_push($errors, "Ip routru je potřeba"); }

    if(DoesExist("ip_adresy", "ip", $ip)){
    array_push($errors, "Zařízení s touto ip již existuje!");
    }elseif (DoesExist("ip_adresy", "Ip_Nazev", $nazev)) {
    array_push($errors, "Ip s tímto názvem již existuje!");
    }  

    if (count($errors) == 0) {
      $query = "INSERT INTO ip_adresy (Ip_Nazev, Nazev_Faze1, Nazev_Faze2, Nazev_Faze3, druh, Vykon, Jednotka, wattrouter_type, Faze_id, ip, port, typ_id, Secret_key_ip) 
      VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      if($res = $conn->prepare($query)){
        $res->bind_param("sssssdiiisiis", $nazev, $Nazev_faze1, $Nazev_faze2, $Nazev_faze3, $druh, $Vykon, $jednotky, $wattrouter_type, $faze_id, $ip, $port, $typ, $secret);
                       
        $jednotky = $_POST['jednotky'];
        $port = $_POST['port']; 
        $wattrouter_type = $_POST['wattrouter-type'];
        $faze_id = $_POST['faze-type'];
        $Nazev_faze1 = $_POST['nameFaze1'];
        $Nazev_faze2 = $_POST['nameFaze2'];
        $Nazev_faze3 = $_POST['nameFaze3'];
        $Vykon = $_POST['vykon'];
        $typ = $_POST['typ'];
        $secret = GenerateKey(73);              
        
        if($res->execute()){        
            echo "Zařízení vytvořeno";
            //mail($email, "Přihlašovací údaje", $msg, $header);
        }else{
            echo "chyba";
            echo $conn->error;
            //header('location: list.php');
        } 
      }else{
        echo $conn->error;
      }
    }  
  } 
?>
<script type="text/javascript" src="./js/mesta.js"></script>
<?php  if (count($errors) > 0) : ?>
  <div class="error">
  	<?php foreach ($errors as $error) : ?>
  	  <p><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>
<form method = "post">

  <div class="row form-group kontrola">
    <div class="col-lg-6 col-md-6 form-group">                  
        <label for="inputNazev">Název </label>
        <input type="text" class="form-control" name="nazev" id="inputNazev" placeholder="Joudárka">         
    </div> 
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
  
  <div class="form-group text-left">    
    <button type="button" id="MyIp" onclick="GetMyIp()" title="Dodá stávající Ipv4" class="templatemo-blue-button">Moje Ip</button> 
  </div>

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Typ zařízení</label>                 
      <select class="select-css form-control" name="typ">
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
              echo "<option value=" . $row['id'] .">" . $row['Nazev'] . "</option>";
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
        <label for="inputVykon">Výkon </label>
        <input type="number" step="0.001" class="form-control" name="vykon" id="inputVykon" placeholder="15">                  
    </div> 
    <div class="col-lg-1 col-md-6 form-group"> 
      <label class="control-label templatemo-block">Jendotka</label>                 
      <select class="select-css form-control" name="jednotky">
        <option value="0" selected>Wp</option>
        <option value="3">KWp</option>                 
      </select>
    </div>     
  </div>  
  
  <div class="form-group text-left">
    <button type="submit" class="templatemo-blue-button" name="register">Přidat</button>
    <button type="reset" class="templatemo-white-button">Reset</button>
  </div> 
</form>
<script>

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
</script>
<?php
  require_once "./footer.inc.php";
?>