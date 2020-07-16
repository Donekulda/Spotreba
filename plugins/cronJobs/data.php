<?php
/*
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
*/
  $base = dirname(dirname(dirname(__FILE__)));
  require_once($base."/config.inc.php");

$timestamp=mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"));

function wattrouter($ip, $port, $settings){
//	$fp=@fsockopen($ip, 80, $errno, $errstr, 5);
//	if($fp){
 //     fclose($fp);
 
   $xml = @simplexml_load_file("http://".$ip.":".$port."/meas.xml");
   if($xml){
    if(strpos($settings['PL1'], '[') !== false){
        $splitStr = explode("[", $settings['PL1']);
        $point = substr($splitStr[1], 0, -1);
        $array["PL1"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["PL1"] = (string)$xml->{$settings['PL1']};
    }

    if(strpos($settings['PL2'], '[') !== false){
        $splitStr = explode("[", $settings['PL2']);
        $point = substr($splitStr[1], 0, -1);
        $array["PL2"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["PL2"] = (string)$xml->{$settings['PL2']};
    }

    if(strpos($settings['PL3'], '[') !== false){
        $splitStr = explode("[", $settings['PL3']);
        $point = substr($splitStr[1], 0, -1);
        $array["PL3"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["PL3"] = (string)$xml->{$settings['PL3']};
    }

    if(strpos($settings['FA1'], '[') !== false){
        $splitStr = explode("[", $settings['FA1']);
        $point = substr($splitStr[1], 0, -1);
        $array["FA1"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["FA1"] = (string)$xml->{$settings['FA1']};
    }

    if(strpos($settings['FA2'], '[') !== false){
        $splitStr = explode("[", $settings['FA2']);
        $point = substr($splitStr[1], 0, -1);
        $array["FA2"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["FA2"] = (string)$xml->{$settings['FA2']};
    }

    if(strpos($settings['FA3'], '[') !== false){
        $splitStr = explode("[", $settings['FA3']);
        $point = substr($splitStr[1], 0, -1);
        $array["FA3"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["FA3"] = (string)$xml->{$settings['FA3']};
    }

    if(strpos($settings['ILT'], '[') !== false){
        $splitStr = explode("[", $settings['ILT']);
        $point = substr($splitStr[1], 0, -1);
        $array["ILT"] = (string)$xml->{$splitStr[0]}->$point;
    }else{
        $array["ILT"] = (string)$xml->{$settings['ILT']};
    }
	}else{
    $array = array("PL1"=>0,"PL2"=>0,"PL3"=>0,"FA1"=>0,"FA2"=>0,"FA3"=>0);
	}
	return $array;
}

function url_exists($url) {
    $h = get_headers($url);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
    return ($status[1] == 200);
}

$sql = "SELECT * FROM ip_adresy";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
  while($row = $result->fetch_assoc()){
    $Ip = $row['ip'];
    $Port = $row['port'];
    $Ip_id = $row['id'];
    $Deactivated = $row['Deactivated'];
    $wattrouterType = $row['wattrouter_type'];
  /*    
  if(!url_exists("http://".$Ip.":".$Port."/meas.xml")){
    die("Ip a nebo port je špatně zadán! Toto platí pro url: ".$Ip.":".$Port);
  }else
    echo "Url existuje! Toto platí pro url: ".$Ip.":".$Port;
  */
    if($Deactivated == 0){
      $wattrouterSettings = array();

      $sql_wattrouter = "SELECT * FROM wattrouter_types WHERE id=".$wattrouterType;
      $result_router = $conn->query($sql_wattrouter);

      if ($result_router->num_rows > 0) {
          // output data of each row
        while($row_router = $result_router->fetch_assoc()){
          $wattrouterSettings["PL1"] = $row_router['PL1'];
          $wattrouterSettings["PL2"] = $row_router['PL2'];
          $wattrouterSettings["PL3"] = $row_router['PL3'];
          $wattrouterSettings["FA1"] = $row_router['FA1'];
          $wattrouterSettings["FA2"] = $row_router['FA2'];
          $wattrouterSettings["FA3"] = $row_router['FA3'];
          $wattrouterSettings["ILT"] = $row_router['ILT'];
        }
      }

      $w0=wattrouter($Ip, $Port, $wattrouterSettings);
      sleep(15);
      $w15=wattrouter($Ip, $Port, $wattrouterSettings);
      sleep(15);
      $w30=wattrouter($Ip, $Port, $wattrouterSettings);
      sleep(15);
      $w45=wattrouter($Ip, $Port, $wattrouterSettings);

      $delitel=4;
      if($w0["PL1"]==0 && $w0["PL2"]==0 && $w0["PL3"]==0) $delitel--;
      if($w15["PL1"]==0 && $w15["PL2"]==0 && $w15["PL3"]==0) $delitel--;
      if($w30["PL1"]==0 && $w30["PL2"]==0 && $w30["PL3"]==0) $delitel--;
      if($w45["PL1"]==0 && $w45["PL2"]==0 && $w45["PL3"]==0) $delitel--;


      if($delitel>0){
        $PL1=($w0["PL1"]+$w15["PL1"]+$w30["PL1"]+$w45["PL1"])/$delitel;
        $PL2=($w0["PL2"]+$w15["PL2"]+$w30["PL2"]+$w45["PL2"])/$delitel;
        $PL3=($w0["PL3"]+$w15["PL3"]+$w30["PL3"]+$w45["PL3"])/$delitel;
        
        $FA1=($w0["FA1"]+$w15["FA1"]+$w30["FA1"]+$w45["FA1"])/$delitel;
        $FA2=($w0["FA2"]+$w15["FA2"]+$w30["FA2"]+$w45["FA2"])/$delitel;
        $FA3=($w0["FA3"]+$w15["FA3"]+$w30["FA3"]+$w45["FA3"])/$delitel;

        $ILT = round(($w0["ILT"]+$w15["ILT"]+$w30["ILT"]+$w45["ILT"])/$delitel);
      } else {
        $PL1=0; $PL2=0; $PL3=0;
        $FA1=0; $FA2=0; $FA3=0;

        $ILT=0;
      }

      if($delitel>1){
        $SP1 = (($PL1 - $FA1 - $FA2 - $FA3)*-1); 
        $SP2 = (($PL2 - $FA1 - $FA2 - $FA3)*-1); 
        $SP3 = (($PL3 - $FA1 - $FA2 - $FA3)*-1); 
      }else{
        $SP1 = (($PL1-$FA1)*-1);
        $SP2 = (($PL2-$FA2)*-1);
        $SP3 = (($PL3-$FA3)*-1);
      }

      $sql = "INSERT INTO data SET
                  datum = ".$timestamp.",
                  PL1 = '".$PL1."',
                  PL2 = '".$PL2."',
                  PL3 = '".$PL3."',
                  FA1 = '".$FA1."',
                  FA2 = '".$FA2."',
                  FA3 = '".$FA3."',
                  SP1 = '".$SP1."',
                  SP2 = '".$SP2."',
                  SP3 = '".$SP3."',
                  NT = ".$ILT.",
                  ip_id = ".$Ip_id;
      
      $conn->query($sql) or die ($conn->error);
    }
  }
}
?>