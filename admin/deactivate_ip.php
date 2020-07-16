<?php
    session_start();
  require_once "../config.inc.php";
  require_once "../functions.inc.php";
  
  $login_url = "../login.php";
  
  if(!IsLogged($title, $login_url))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") < 2){
    header("location: ../error.php");
  }
  
  $id = (int)$_GET['id'];
  if($id < 0)
    $id = 0;
  $key = $_GET['Secret_key'];
  $deactivate = (int)$_GET['deactivate'];
  
  if(!($Data_key = GetVariable($id, "ip_adresy", "Secret_key_ip"))){ //Kontroluje zda dostal Secret_key z database
    echo $conn->error;
  }
        
  if($key != $Data_key){ // Porovnává klíče
    echo "<div class = 'error center'>No tak to je blblý kámo,<br />tvůj zadaný klíč je špatný!!!</div>";  
  }else{
    if($deactivate > 0)
        $sql = "UPDATE ip_adresy SET Deactivated=1 WHERE id=".$id;
    else
        $sql = "UPDATE ip_adresy SET Deactivated=0 WHERE id=".$id;
    
    if ($conn->query($sql) === TRUE) {
        if($deactivate > 0)
            header("location: ./zarizeni.php");  
        else
            header("location: ./reaktivace_ip.php");
    } else {
      echo "Error updating record: " . $conn->error;
    } 
  }  
  require_once "./menu.inc.php";
  
  require_once "../footer.inc.php";
?>