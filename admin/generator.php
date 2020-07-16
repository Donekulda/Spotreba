<?php
  require_once "../functions.inc.php";
  require_once "../config.inc.php";
  
  //echo GenerateKey(75);
  // Pro případ nouze
  
  if(isset($_POST['generate'])){
    $cislo = GenerateKey(73);
    $heslo = password_hash($cislo,PASSWORD_DEFAULT);
    echo $cislo;
    /*
    $sql = "UPDATE uzivatele SET heslo=? WHERE id=";
  
    if($res = $conn->prepare($sql)){
      $res->bind_param("s",$heslo);
      if($res->execute()){
        echo "<br />".$cislo."<br />";
      }else
        echo $conn->error;  
    }else
      echo $conn->error; 
    */
    //ChangeString(, "uzivatele", "heslo", $heslo);
  }
  
?>
<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Generator</title>
  </head>
  <body>  
    <form method="post" action="">
      <input type="submit" name="generate" Value="Vygeneruj heslo">
    </form>
  </body>
</html>
