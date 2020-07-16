<?php
    session_start();
  require_once "../config.inc.php";
  require_once "../functions.inc.php";
  
  $login_url = "../login.php";
  
  if(!IsLogged($title, $login_url))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") >= 2){
  
    $id = (int)$_GET['id'];
      $sql = "DELETE FROM wattrouter_types WHERE id=".$id;
      
      if ($conn->query($sql) === TRUE) {
        header("location: ./wattrouter_type_manager.php");  
      } else {
        echo "Error deleting record: " . $conn->error;
      } 
    }  

  }else{
    header("location: ../error.php");
  }
  require_once "./menu.inc.php";
  
  require_once "../footer.inc.php";
?>