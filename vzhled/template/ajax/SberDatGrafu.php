<?php
    session_start();
    require_once "../app/config.inc.php";

    $faze = (int)$_GET['faze'];
    $od = (int)$_GET['od'];
    $max = (int)$_GET['max'];
    if(isset($_GET['ip'])){
      $_SESSION['show_ip'] = (int)$_GET['ip'];
    }
    $akturalni_vyvoj = false;
    
    if(!isset($od)||(int)$od==0){
        $timestamp = mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"))-60;
        $od = $timestamp-6*3600;
        $do = $timestamp;
        $akturalni_vyvoj = true;
      }else{
        $timestamp = (int)$od+86400;
        $od = (int)$od;
        $do = (int)$od+86400;
      }
      
      $krok = 60 * ceil(($do-$od)/86400);
      
      if($faze>0){$sqlSP = "SP".$faze; $sqlFA = "FA".$faze;}
      elseif($faze==-1){$sqlSP = "(SP1+SP2+SP3)"; $sqlFA = "(FA1+FA2+FA3)";}
      else exit;
      $sql = "SELECT datum, IF(NT=1, -".$sqlSP.", ".$sqlSP.") AS SP, ".$sqlFA." AS FA FROM data WHERE ip_id=".$_SESSION['show_ip']." AND (datum >= ".($od-$krok)." AND datum <= ".$do.")";
      //$query = mysqli_query($conn,"SELECT datum, IF(NT=1, -".$sqlSP.", ".$sqlSP.") AS SP, ".$sqlFA." AS FA FROM data WHERE ip_id=".$_SESSION['show_ip']." AND (datum >= ".($od-$krok)." AND datum <= ".$do.")");
      if($query = $conn->query($sql)){
        while($radek=mysqli_fetch_assoc($query)){
          foreach(array_keys($radek) as $k){
                if($k=="datum") continue;
              @$BUFF[$k]+=$radek[$k]*1000;
              @$COUNT[$k]++;
              if(($radek["datum"]-$od)%$krok==0){
                  $ddata[$radek["datum"]][$k] = ROUND($BUFF[$k]/$COUNT[$k],1);
                  unset($BUFF[$k],$COUNT[$k]);
                }
          }
        }
      }else{
        die($conn->error);
      }
      
      if(!isset($ddata[$od-$krok])) $ddata[$od-$krok] = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
      $min_leva = 10000;
      $max_leva = isset($max)?$max:1;
      
      $i=$od;
      while($i<=$do){
        if(isset($ddata[$i]))
           foreach((array)$ddata[$i] as $kan => $hodnota){
            if(isset($ddata[$i][$kan]) && abs($ddata[$i][$kan])>$max_leva) $max_leva = abs($hodnota);
              if(isset($ddata[$i][$kan]) && $ddata[$i][$kan]<$min_leva) $min_leva = $hodnota;    
            }
        $i+=$krok;
      }    

      $min_leva=0;
      
      $osa_y_leva = $max_leva-$min_leva;
      
      if($akturalni_vyvoj)
        $souhrny = mysqli_query($conn,"SELECT SUM(".$sqlSP.") SP, SUM(".$sqlFA.") FA, COUNT(*) I FROM data WHERE ip_id=".$_SESSION['show_ip']." AND datum >= ".mktime(0,0,0,date("n"),date("j"),date("Y")));
      else
        $souhrny = mysqli_query($conn,"SELECT SUM(".$sqlSP.") SP, SUM(".$sqlFA.") FA, COUNT(*) I FROM data WHERE ip_id=".$_SESSION['show_ip']." AND datum BETWEEN ".$od." AND ".$do." ");
      
      $souhrn = mysqli_fetch_assoc($souhrny);

      if($info_sql = mysqli_query($conn, "SELECT druh, Vykon, Jednotka FROM ip_adresy WHERE id=".$_SESSION['show_ip'])){
        $info = mysqli_fetch_assoc($info_sql);
      }else{
        echo $conn->error;
      }
      
      $jednotka = "";
      switch ($info['Jednotka']){
        case 0:
          $jednotka = "Wp";
          break;
        case 3:
          $jednotka = "kWp";
          break;
        case 6:
          $jednotka = "MWp";
          break;
        default:
          $jednotka = $info['Jednotka'];
      }      
      
      /* END SBĚR DAT*/ 
      
      $kanal_FA = "#368e1f";  // výroba FA orámování
      $kanal0_SP = "red";  // spotřeba SP orámování
      $kanal1_SP = "blue";  // spotřeba SP orámování
      $DataPointsSP = array();
      $DataPointsFA = array();
      
      $DataId = 0;
      for($i=$od;$i<=$do;$i+=$krok) {
        if($i>$timestamp) break;
        if(isset($ddata[$i])){
          if($ddata[$i]['SP']<0) $NT=1; else $NT=0;

          //Data pro SP
          $DataPointsSP[$DataId][0] = $i*1000; // x = cas
          $DataPointsSP[$DataId][1] = abs($ddata[$i]['SP']); // y  =  Hodnoty
          $DataPointsSP[$DataId][2] = ${"kanal".$NT."_SP"}; // color

          //Data pro FA
          $DataPointsFA[$DataId][0] = $i*1000; // x = cas
          $DataPointsFA[$DataId][1] = abs($ddata[$i]['FA']); // y  =  Hodnoty
          $DataPointsFA[$DataId][2] = $kanal_FA; // color

          $DataId += 1;
        }
      }
                

      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////                                        //////////////////////////////////////////////
      ////////////////////////////////////////           Object with data                    ///////////////////////////////////////////
      ////////////////////////////////////////////                                        //////////////////////////////////////////////
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $myObj = null;

      $myObj->odKdy = $od * 1000;
      $myObj->doKdy = $do * 1000;
      $myObj->dataSP = $DataPointsSP;
      $myObj->dataFA = $DataPointsFA;
      $myObj->souhrnSP = @round($souhrn["SP"]/60,3);
      $myObj->souhrnFA = @round($souhrn["FA"]/60,3);
      $myObj->vykon = $info["Vykon"]." ".$jednotka;

      $myJSON = json_encode($myObj);

      echo $myJSON;
?>