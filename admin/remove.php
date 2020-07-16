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
  $key = $_GET['Secret_key'];
  
  if(!($Data_key = GetVariable($id, "uzivatele", "Secret_key"))){ //Kontroluje zda dostal Secret_key z database
    echo $conn->error;
  }
        
  if($key != $Data_key){ // Porovnává klíče
    echo "<div class = 'error center'>No tak to je blblý kámo,<br />tvůj zadaný klíč je špatný!!!</div>";              
  }else{
    $sql = "DELETE FROM uzivatele WHERE id=".$id;
    
    if ($conn->query($sql) === TRUE) {
      header("location: ./uzivatele.php");  
    } else {
      echo "Error deleting record: " . $conn->error;
    } 
  }  
  require_once "./menu.inc.php";
  
  require_once "../footer.inc.php";
?>