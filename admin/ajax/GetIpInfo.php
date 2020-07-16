<?php
$base = dirname(dirname(dirname(__FILE__)));
require_once($base."/config.inc.php");

    function GetVariable($id, $table, $category){
      if(isset($_SESSION['connection'])){
          $sql = "SELECT ".$category." FROM ".$table." WHERE id=?"; 
          if($stmt = $_SESSION['connection']->prepare($sql)){
              $stmt->bind_param('d', $id);
              if($stmt->execute()){
                  $stmt->store_result();
                  if($stmt->num_rows > 0){ 
                      $stmt->bind_result($value);
                      $stmt->fetch();
                      return $value;   
                  }
              }else{
                  echo $stmt->error;
                  return null;
              }
          }
          echo $_SESSION['connection']->error;
          return null;
      }
      return null;  
    }

    $id = (int)$_GET['id'];
    $Data_key = GetVariable($id, "ip_adresy", "Secret_key_ip");
    $myObj = null;

    if($Data_key == $_GET['secret']){
        $sql = "SELECT Ip_Nazev, Nazev_Faze1, Nazev_Faze2, Nazev_Faze3, druh, ip, port, wattrouter_type, typ_id, Faze_id, Vykon, Jednotka, wattrouter_type
                FROM ip_adresy 
                WHERE ip_adresy.id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($Nazev, $Nazev_faze1, $Nazev_faze2, $Nazev_faze3, $druh, $ip, $port, $wattrouter_type, $typ, $faze_id, $Vykon, $Jednotka, $wattrouter_type);
        $stmt->fetch();
        $stmt->close();

        @$myObj->Nazev = $Nazev;
        $myObj->Druh = $druh;
        $myObj->Ip = $ip;
        $myObj->Port = (int)$port;
        $myObj->Typ = (int)$typ;
        $myObj->Vykon = (double)$Vykon;
        $myObj->Jednotka = (int)$Jednotka;
        $myObj->Wattrouter = (int)$wattrouter_type;
        $myObj->faze = (int)$faze_id;
        $myObj->Name_faze1 = $Nazev_faze1;
        $myObj->Name_faze2 = $Nazev_faze2;
        $myObj->Name_faze3 = $Nazev_faze3;
    }else{
        @$myObj->Nazev = "Error";
        $myObj->Druh = "Error";
        $myObj->Ip = "Error";
        $myObj->Port = 9999;
        $myObj->Typ = 1;
        $myObj->Vykon = 99999;
        $myObj->Jednotka = 0;
        $myObj->Wattrouter = 1;
        $myObj->faze = 1;
        $myObj->Name_faze1 = "";
        $myObj->Name_faze2 = "";
        $myObj->Name_faze3 = "";
    }

    $myJSON = json_encode($myObj);

    echo $myJSON;
?>