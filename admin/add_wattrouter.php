<?php
  $title = "Registrace ip adresy";
  require_once "./header.inc.php";
  
  $nazev = "";
  $druh = "";
  $ip = "";
  $port = $default_port;
  $Vykon = 0.0;
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
    $Nazev = mysqli_real_escape_string($conn, $_POST['nazev']);
    $PL1 = mysqli_real_escape_string($conn, $_POST['PL1']);
    $PL2 = mysqli_real_escape_string($conn, $_POST['PL2']);
    $PL3 = mysqli_real_escape_string($conn, $_POST['PL3']);
    $FA1 = mysqli_real_escape_string($conn, $_POST['FA1']);
    $FA2 = mysqli_real_escape_string($conn, $_POST['FA2']);
    $FA3 = mysqli_real_escape_string($conn, $_POST['FA3']);
    $ILT = mysqli_real_escape_string($conn, $_POST['ILT']);

    // za každou nevyplněnou kolonku přidáme si do pole errors error
    if (empty($Nazev)) { array_push($errors, "Název je potřeba vyplnit"); }
    if (empty($PL1)) { array_push($errors, "PL1 je potřeba vyplnit"); }
    if (empty($FA1)) { array_push($errors, "FA1 routru je potřeba"); }

    if(DoesExist("ip_adresy", "ip", $ip)){
    array_push($errors, "Zařízení s touto ip již existuje!");
    }elseif (DoesExist("ip_adresy", "Ip_Nazev", $nazev)) {
    array_push($errors, "Ip s tímto názvem již existuje!");
    }  

    if (count($errors) == 0) {
      $query = "INSERT INTO wattrouter_types (Nazev, PL1, PL2, PL3, FA1, FA2, FA3, ILT) 
      VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
      if($res = $conn->prepare($query)){
        $res->bind_param("ssssssss",$Nazev, $PL1, $PL2, $PL3, $FA1, $FA2, $FA3, $ILT);
        
        if($res->execute()){        
            echo "Typ wattrouteru byl úspěšně přidán!";
        }else{
            echo "chyba";
            echo $conn->error;
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
      <input type="text" class="form-control" name="nazev" id="inputNazev" placeholder="Wattrouter M..." required>         
    </div> 
  </div>

  <div class="row form-group">
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputPL1">PL1 </label>
      <input type="text" class="form-control" name="PL1" id="inputPL1" placeholder="PL..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputPL2">PL2 </label>
      <input type="text" class="form-control" name="PL2" id="inputPL2" placeholder="PL..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputPL3">PL3 </label>
      <input type="text" class="form-control" name="PL3" id="inputPL3" placeholder="PL..." required>                  
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputFA1">FA1 </label>
      <input type="text" class="form-control" name="FA1" id="inputFA1" placeholder="FA..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputFA2">FA2 </label>
      <input type="text" class="form-control" name="FA2" id="inputFA2" placeholder="FA..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputFA3">FA3 </label>
      <input type="text" class="form-control" name="FA3" id="inputFA3" placeholder="FA..." required>                  
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputILT">ILT </label>
        <input type="text" class="form-control" name="ILT" id="inputILT" placeholder="ILT..." required>                  
    </div> 
  </div>  
  
  <div class="form-group text-left">
    <button type="submit" class="templatemo-blue-button" name="register">Přidat</button>
    <button type="reset" class="templatemo-white-button">Reset</button>
  </div> 
</form>
<?php
  require_once "./footer.inc.php";
?>