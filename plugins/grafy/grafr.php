<?php
/* část kodu pro zobrazení chyb PS: musi se zakomentářovat header pokud hceš zobrazit chyby
  error_reporting(-1);
  ini_set('display_errors', 'On');
*/

header ("Content-type: image/png;");
session_start();
$base = dirname(dirname(dirname(__FILE__)));
require_once($base."/config.inc.php");

// $_GET["od"] ve formátu 4-2015
  //  echo "jop";
if(!isset($_GET["rok"])){
  exit;
}else{
  $rok = (int)$_GET['rok'];
}

$faze = (int)$_GET["faze"];
$pocetVstupu = (int)$_GET["pVstupu"];
$pocetFazi = (int)$_GET['delitel'];
$max = (int)$_GET['max'];

$delitel = 1;
if(isset($pocetFazi))
  $delitel = $pocetFazi;

if($faze == -1)
  $delitel = 1;

function hexColorAllocate($im,$hex){
  $hex = ltrim($hex,'#');
  $a = hexdec(substr($hex,0,2));
  $b = hexdec(substr($hex,2,2));
  $c = hexdec(substr($hex,4,2));
  return imagecolorallocate($im, $a, $b, $c); 
}

$vyska = 320;
$sirka = 665;
$vyska_img = $vyska+60;
$sirka_img = $sirka+40;
$mesicu = 12; // Celý rok

if($faze>0){$sqlSP = "SUM(PL".$faze.")"; $sqlFA = "SUM(FA".$faze.")";}
elseif($faze==-1){$sqlSP = "SUM(PL1+PL2+PL3)"; $sqlFA = "SUM(FA1+FA2+FA3)";}
else exit;

@$query = mysqli_query($conn,"SELECT MONTH(FROM_UNIXTIME(datum)) as m, ".$sqlSP." AS SP, ".$sqlFA." AS FA, SUM(FA1) FVE1, SUM(FA2) FVE2, SUM(FA3) FVE3 FROM data WHERE ip_id=".$_SESSION['show_ip']." AND YEAR(FROM_UNIXTIME(datum)) = ".$rok." GROUP BY m");
while($radek=mysqli_fetch_assoc($query)){
  $ddata[$radek["m"]] = array("SP"=>round($radek["SP"]/60,3),"FA"=>round($radek["FA"]/60),"FVE1"=>round($radek["FVE1"]/60),"FVE2"=>round($radek["FVE2"]/60),"FVE3"=>round($radek["FVE3"]/60));
  @$max["sp"]=max(@$max["sp"],$radek["SP"]);
  @$max["fa"]=max(@$max["fa"],$radek["FA"]);
}

$max_leva = isset($_GET["max"])?$_GET["max"]:max($max["sp"]/60,$max["fa"]/60);
$min_leva=0;
$osa_y_leva = $max_leva-$min_leva;

/* END SBĚR DAT*/


$im = @imagecreatetruecolor($sirka_img, $vyska_img);
imagefilledrectangle ($im, 1, 1,$sirka_img-2,$vyska_img-2, imagecolorallocate($im, 255, 255, 255)); // šedá => 235, 235, 235 

//barvicky
$cerna = imagecolorallocate($im, 5, 5, 5); //osy
$seda = imagecolorallocate($im, 195, 195, 195); //osy
$kanal_FA = hexColorAllocate($im, "#007000");  //vyroba FA
$kanal_SP = imagecolorallocate($im, 243, 83, 49);  // spotřeba SP
$poleZelenych = ["#00FF00","#00D100","#00AB00","#009000"];

// X popisky
for($i=1;$i<=$mesicu;$i++){
  imagestring($im, 1, (17+($i * $sirka/$mesicu)), $vyska+10, $i, $cerna);
  imageline($im, (25+($i * $sirka/$mesicu)), 10, (25+($i * $sirka/$mesicu)), $vyska+5, $seda); //Y
}

// Y popisky  + X OSY
imagettftext($im, 8, 90, 12, @round($vyska/2 + ($vyska/20)), $cerna, './arial.ttf', "kWh");
for($i=0;$i<=10;$i++){
  imageline($im, 30, round($vyska*(1-$i/11)), $sirka+30, round($vyska*(1-$i/11)), $seda); //X
  imagestring($im, 1, 18, round($vyska*(1-$i/11)-5), round($min_leva+$osa_y_leva/10*$i), $cerna);
}

//osy
imageline($im, 25, $vyska, $sirka+35, $vyska, $cerna); //X


$mezera_mezi_body_Y_leva = $vyska/$osa_y_leva*10/11;




for($k=1;$k<=$mesicu;$k++){
  if(!isset($ddata[$k]["SP"])) continue;
   if($pocetFazi>1){
     $FVE = 0.0;
      for($j = 1; $j <= $pocetVstupu; $j++){
        $y_bod["FVE".$j] = floor((($max_leva-$ddata[$k]["FVE".$j]/$delitel))*$mezera_mezi_body_Y_leva+$vyska/11);
        $FVE += ($ddata[$k]["FVE".$j]/$delitel);
      }
      $y_bod["FA"] = floor(($max_leva-$FVE)*$mezera_mezi_body_Y_leva+$vyska/11);
      $y_bod["SP"] = floor(($max_leva-abs($ddata[$k]["SP"] - $FVE))*$mezera_mezi_body_Y_leva+$vyska/11);
   }else{
    $y_bod["FA"] = floor(($max_leva-$ddata[$k]["FA"])*$mezera_mezi_body_Y_leva+$vyska/11);
    $y_bod["SP"] = floor(($max_leva-abs($ddata[$k]["SP"] - $ddata[$k]["FA"]))*$mezera_mezi_body_Y_leva+$vyska/11);
   }
   imagefilledrectangle ($im, (25+($k * $sirka/$mesicu)), $vyska, (31+($k * $sirka/$mesicu)),  $y_bod["SP"] ,$kanal_SP);
   imagefilledrectangle ($im, (18+($k * $sirka/$mesicu)), $vyska, (24+($k * $sirka/$mesicu)),  $y_bod["FA"] ,$kanal_FA);

   if($pocetFazi>1){
     for($j = $pocetVstupu; $j >= 1; $j--){
      imagefilledrectangle ($im, (18+($k * $sirka/$mesicu)), $vyska, (24+($k * $sirka/$mesicu)),  $y_bod["FVE".$j] ,hexColorAllocate($im, $poleZelenych[$j]));

      @$souhrn["FVE".$j]+= $ddata[$k]["FVE".$j]/$delitel;
      @$souhrn["FA"]+= $ddata[$k]["FVE".$j]/$delitel;
     }
   }else{
    @$souhrn["FA"]+= $ddata[$k]["FA"];
   }
   @$souhrn["SP"]+= $ddata[$k]["SP"];

}

$k=1;

imagettftext($im, 11, 0, 35, $vyska_img-22, $kanal_FA, './arial.ttf', "Roční výroba: ".@round($souhrn["FA"],1)." kWh");
if($pocetFazi>1){
  for($j = 1; $j <= $pocetVstupu; $j++){
    imagettftext($im, 11, 0, 35+(125*($j-1)), $vyska_img-4, hexColorAllocate($im, $poleZelenych[$j]), './arial.ttf', "FVE".$j.": ".@round($souhrn["FVE".$j],1)." kWh");
  }
}
imagettftext($im, 11, 0, 435, $vyska_img-22, $kanal_SP, './arial.ttf', "Odběr od distributora: ".@round(abs($souhrn["SP"]),1)." kWh");
imagettftext($im, 11, 0, 435, $vyska_img-4, $kanal_SP, './arial.ttf', "Celková spotřeba: ".@round(abs($souhrn["SP"] - $souhrn["FA"]),1)." kWh");

imagepng($im);
imagedestroy($im);
?>