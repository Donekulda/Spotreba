<?php
    session_start();
  require_once "../config.inc.php";
  require_once "../functions.inc.php";
  
  if(!IsLogged($title))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") < 2){
    header("location: ../error.php");
  }
    
  $id = (int)$_GET['id']; //Id právě upravujícího profilu

  $title = "Úprava wattrouteru typu";
  
  $opravneni_editor = GetVariable($_SESSION['id'], "uzivatele", "opravneni");//oprávnění editora zvoleného účtu

  if(isset($id)){
    $sql = "SELECT * FROM wattrouter_types WHERE id=".$id;
    if ($result = $conn->query($sql)) {
      while ($row = $result->fetch_array()) {
        $Nazev = $row['Nazev'];
        $PL1 = $row['PL1'];
        $PL2 = $row['PL2'];
        $PL3 = $row['PL3'];
        $FA1 = $row['FA1'];
        $FA2 = $row['FA2'];
        $FA3 = $row['FA3'];
        $ILT = $row['ILT'];
      }
    }
  }
  

  if(isset($_POST['edit'])){
    if(isset($_POST['nazev']) && $_POST['nazev'] != $Nazev){
      ChangeString($id, "wattrouter_types", "Nazev", $_POST['nazev']);
    }
    if(isset($_POST['FA1']) && $_POST['FA1'] != $FA1){
      ChangeString($id, "wattrouter_types", "FA1", $_POST['FA1']);
    }
    if(isset($_POST['FA2']) && $_POST['FA2'] != $FA2){
      ChangeString($id, "wattrouter_types", "FA2", $_POST['FA2']);
    }
    if(isset($_POST['FA3']) && $_POST['FA3'] != $FA3){
      ChangeString($id, "wattrouter_types", "FA3", $_POST['FA3']);
    }
    if(isset($_POST['PL1']) && $_POST['PL1'] != $PL1){
      ChangeString($id, "wattrouter_types", "PL1", $_POST['PL1']);
    }
    if(isset($_POST['PL2']) && $_POST['PL2'] != $PL2){
      ChangeString($id, "wattrouter_types", "PL2", $_POST['PL2']);
    }
    if(isset($_POST['PL3']) && $_POST['PL3'] != $PL3){
      ChangeString($id, "wattrouter_types", "PL3", $_POST['PL3']);
    }
    if(isset($_POST['ILT']) && $_POST['ILT'] != $ILT){
      ChangeString($id, "wattrouter_types", "ILT", $_POST['ILT']);
    }

    header("Location: ./wattrouter_type_manager.php");
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
  <body>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script type="text/javascript" src="./js/mesta.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> 
  <div class="templatemo-flex-row">
  <?php 
    require_once "./menu.inc.php";   
  ?>
<form method = "post"> 
  
<div class="row form-group kontrola">
    <div class="col-lg-6 col-md-6 form-group">                  
      <label for="inputNazev">Název </label>
      <input type="text" class="form-control" name="nazev" id="inputNazev" value="<?php echo $Nazev;?>" placeholder="Wattrouter M..." required>         
    </div> 
  </div>

  <div class="row form-group">
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputPL1">PL1 </label>
      <input type="text" class="form-control" name="PL1" id="inputPL1" value="<?php echo $PL1;?>" placeholder="PL..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputPL2">PL2 </label>
      <input type="text" class="form-control" name="PL2" id="inputPL2" value="<?php echo $PL2;?>" placeholder="PL..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputPL3">PL3 </label>
      <input type="text" class="form-control" name="PL3" id="inputPL3" value="<?php echo $PL3;?>" placeholder="PL..." required>                  
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputFA1">FA1 </label>
      <input type="text" class="form-control" name="FA1" id="inputFA1" value="<?php echo $FA1;?>" placeholder="FA..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputFA2">FA2 </label>
      <input type="text" class="form-control" name="FA2" id="inputFA2" value="<?php echo $FA2;?>" placeholder="FA..." required>                  
    </div>
    <div class="col-lg-1 col-md-6 form-group">                  
      <label for="inputFA3">FA3 </label>
      <input type="text" class="form-control" name="FA3" id="inputFA3" value="<?php echo $FA3;?>" placeholder="FA..." required>                  
    </div>
  </div>

  <div class="row form-group">
    <div class="col-lg-2 col-md-6 form-group">                  
        <label for="inputILT">ILT </label>
        <input type="text" class="form-control" name="ILT" id="inputILT" value="<?php echo $ILT;?>" placeholder="ILT..." required>                  
    </div> 
  </div>  

  <div class="form-group text-left">
    <button type="submit" class="templatemo-blue-button" name="edit">Upravit</button>
    <button type="reset" class="templatemo-white-button">Reset</button>
  </div> 

</form>
<?php

  require_once "./footer.inc.php";
?>