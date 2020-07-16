<?php
    session_start();
    require_once "../config.inc.php";

    $faze = (int)$_GET['faze'];
    $fazeTyp = (int)$_GET['TypFaze'];
    $od = (int)$_GET['od'];
    $max = (int)$_GET['max'];
    $pocetVykonu = (int)$_GET['pocet'];
    if(isset($_GET['ip'])){
      $_SESSION['show_ip'] = (int)$_GET['ip'];
    }
    if(!isset($fazeTyp))
      $fazeTyp = 1;
    
    if($faze == -1){
      $delitel = 1;
    }else{
      $delitel = $fazeTyp;
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
      
      if($faze>0){$sqlSP = "PL".$faze; $sqlFA = "FA".$faze;}
      elseif($faze==-1){$sqlSP = "(PL1+PL2+PL3)"; $sqlFA = "(FA1+FA2+FA3)";}
      else exit;

      if($fazeTyp>1){
        $sqlSP = "(PL1+PL2+PL3)";
        if($faze>0){
          $sqlFA = "(FA1/".$fazeTyp."+FA2/".$fazeTyp."+FA3/".$fazeTyp.")";
          $sqlSP = "PL".$faze;
        }
        $sql = "SELECT datum, ".$sqlSP." AS SP, FA1, FA2, FA3, NT FROM data WHERE ip_id=".$_SESSION['show_ip']." AND (datum >= ".($od-$krok)." AND datum <= ".$do.")";
      }else{
        $sql = "SELECT datum, ".$sqlSP." AS SP, ".$sqlFA." AS FA, NT FROM data WHERE ip_id=".$_SESSION['show_ip']." AND (datum >= ".($od-$krok)." AND datum <= ".$do.")";
      }
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
        $souhrny = mysqli_query($conn,"SELECT SUM(CASE WHEN ".$sqlSP." < '0' THEN ".$sqlSP." ELSE 0 END) SP, SUM(".$sqlFA.") FA, SUM(FA1/".$delitel.") AS ANDI1, SUM(FA2/".$delitel.") AS ANDI2, SUM(FA3/".$delitel.") AS ANDI3, COUNT(*) I FROM data WHERE ip_id=".$_SESSION['show_ip']." AND datum >= ".mktime(0,0,0,date("n"),date("j"),date("Y")));
      else
        $souhrny = mysqli_query($conn,"SELECT SUM(CASE WHEN ".$sqlSP." < '0' THEN ".$sqlSP." ELSE 0 END) SP, SUM(".$sqlFA.") FA, SUM(FA1/".$delitel.") AS ANDI1, SUM(FA2/".$delitel.") AS ANDI2, SUM(FA3/".$delitel.") AS ANDI3, COUNT(*) I FROM data WHERE ip_id=".$_SESSION['show_ip']." AND datum BETWEEN ".$od." AND ".$do." ");
      
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
      
      $kanal_FA = "#007000";  // výroba FA orámování
      $kanal0_SP = "red";  // spotřeba SP orámování
      $kanal1_SP = "blue";  // spotřeba SP orámování
      $DataPointsSP = array();
      $DataPointsFA = array();

      $poleNT = array();
      $pretok = 0;
      $pocetPretoku = 0;

      $DataId = 0;
      for($i=$od;$i<=$do;$i+=$krok) {
        if($i>$timestamp) break;
        if(isset($ddata[$i])){
          //if($ddata[$i]['SP']<0) $NT=1; else $NT=0;
          $NT = $ddata[$i]['NT']/1000;
          $pripocetFA = 0.0;

          array_push($poleNT, $NT);
          //Data pro SP

          //Data pro FA
          $posun_Dat = 0;
          if($fazeTyp > 1){
            $poleZelenych = ["#00FF00","#00D100","#00AB00","#009000"];
            for($k = 0; $k < $pocetVykonu; $k++){
              ${"DataPointsFA".$k}[$DataId][0] = $i*1000; // x = cas
              ${"DataPointsFA".$k}[$DataId][1] = @round((abs($ddata[$i]['FA'.($k+1)])/$delitel), 0); // y  =  Hodnoty
              ${"DataPointsFA".$k}[$DataId][2] = $poleZelenych[$k]; // color

              $pripocetFA += $ddata[$i]['FA'.($k+1)]/$delitel;
            }
            $posun_Dat = $pocetVykonu;
          }else{
            $pripocetFA = $ddata[$i]['FA'];
          }
          
          ${"DataPointsFA".$posun_Dat}[$DataId][0] = $i*1000; // x = cas
          if($fazeTyp>1)// y  =  Hodnoty
            ${"DataPointsFA".$posun_Dat}[$DataId][1] = @round(abs($ddata[$i]['FA1'])/$delitel + abs($ddata[$i]['FA2'])/$delitel + abs($ddata[$i]['FA3'])/$delitel, 0);
          else
            ${"DataPointsFA".$posun_Dat}[$DataId][1] = @round(abs($ddata[$i]['FA']), 1);
          ${"DataPointsFA".$posun_Dat}[$DataId][2] = $kanal_FA; // color
          
          $DataPointsSP[$DataId][0] = $i*1000; // x = cas
          $DataPointsSP[$DataId][1] = @round(abs($ddata[$i]['SP'] - $pripocetFA), 0); // y  =  Hodnoty
          $DataPointsSP[$DataId][2] = ${"kanal".$NT."_SP"}; // color

          if($ddata[$i]['SP'] > 0){
            $pretok += $ddata[$i]['SP'];
            $pocetPretoku++;
          }
          $DataId += 1;
        }
      }
                

      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////                                        //////////////////////////////////////////////
      ////////////////////////////////////////           Object with data                    ///////////////////////////////////////////
      ////////////////////////////////////////////                                        //////////////////////////////////////////////
      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $myObj = null;

     @$myObj->odKdy = $od * 1000;
      $myObj->doKdy = $do * 1000;
      $myObj->dataSP = $DataPointsSP;
      if($fazeTyp > 1){
        $ArrayOfANDI = array();

        for($i = 1; $i <= $pocetVykonu; $i++){
          array_push($ArrayOfANDI,@round($souhrn["ANDI".$i]/60,1));
        }

        $myObj->vykonANDI = $ArrayOfANDI;

        for($i = 0; $i <= $pocetVykonu; $i++){
          $myObj->{"dataFA".$i} = ${"DataPointsFA".$i};
        }
      }else
        $myObj->dataFA = $DataPointsFA0;
      $myObj->souhrnSP = @round(abs($souhrn["SP"])/60,1);
      $myObj->souhrnSumSP = @round((($souhrn["SP"] - $souhrn["FA"])*(-1) - abs($pretok/1000))/60, 1);
      $myObj->pretokSum = @round(abs($pretok/1000)/60,1);
      $myObj->pretokPocet = $pocetPretoku;
      $myObj->souhrnFA = @round($souhrn["FA"]/60,1);
      $myObj->vykon = $info["Vykon"]." ".$jednotka;
      $myObj->NT=$poleNT;

      $myJSON = json_encode($myObj);
      echo $myJSON;
?>