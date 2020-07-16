<?php
  function CreateGraf($id_div, $faze, $od, $max_zad){
    global $conn;
// $_GET["od"] ve formátu 4-2015
  //  echo "jop";
  $od_datove = $od;
  if(!isset($od)){
    exit;
  }else{
    $get = explode("-",$od);
  
    $od = mktime(0,0,0,(int)$get["0"],1,(int)$get["1"]);
    $do = mktime(0,0,0,(int)$get["0"]+1,1,(int)$get["1"]);
  }
  
  $vyska = 270;
  $sirka = 615;
  $vyska_img = $vyska+60;
  $sirka_img = $sirka+40;
  $dnu = date("t",$od+86400);
  
  if($faze>0){$sqlSP = "SUM(SP".$faze.")"; $sqlFA = "SUM(FA".$faze.")";}
  elseif($faze==-1){$sqlSP = "SUM(SP1+SP2+SP3)"; $sqlFA = "SUM(FA1+FA2+FA3)";}
  else exit;
  
  $query = mysqli_query($conn,"SELECT DAY(FROM_UNIXTIME(datum)) d, ".$sqlSP." AS SP, ".$sqlFA." AS FA FROM data WHERE ip_id=".$_SESSION['show_ip']." AND datum >= ".$od." AND datum < ".$do." GROUP BY(DAY(FROM_UNIXTIME(datum)))");
  while($radek=mysqli_fetch_assoc($query)){
    $ddata[$radek["d"]] = array("SP"=>round($radek["SP"]/60,3),"FA"=>round($radek["FA"]/60));
    @$max["sp"]=max(@$max["sp"],$radek["SP"]);
    @$max["fa"]=max(@$max["fa"],$radek["FA"]);
  }
  
  $max_leva = isset($max_zad)?$max_zad:max($max["sp"]/60,$max["fa"]/60);
  $min_leva=0;
  $osa_y_leva = $max_leva-$min_leva;
  
  /* END SBĚR DAT*/
  
  /*
  $im = @imagecreatetruecolor($sirka_img, $vyska_img);
  imagefilledrectangle ($im, 1,1,$sirka_img-2,$vyska_img-2, imagecolorallocate($im, 235, 235, 235));
  //barvicky
  $cerna = imagecolorallocate($im, 5, 5, 5); //osy
  $seda = imagecolorallocate($im, 195, 195, 195); //osy
  $kanal_FA = imagecolorallocate($im, 64, 128, 0);  //vyroba FA
  $kanal_SP = imagecolorallocate($im, 243, 83, 49);  // spotřeba SP
  
  
  // X popisky
  for($i=1;$i<=$dnu;$i++){
    imagestring($im, 1, (17+($i * $sirka/$dnu)), $vyska+10, $i, $cerna);
    imageline($im, (20+($i * $sirka/$dnu)), 10, (20+($i * $sirka/$dnu)), $vyska+5, $seda); //Y
  }
  
  $mezera_mezi_body_Y_leva = $vyska/$osa_y_leva*10/11;
  
  for($k=1;$k<=$dnu;$k++){
    if(!isset($ddata[$k]["SP"])) continue;
     $y_bod["SP"] = floor(($max_leva-$ddata[$k]["SP"])*$mezera_mezi_body_Y_leva+$vyska/11);
     $y_bod["FA"] = floor(($max_leva-$ddata[$k]["FA"])*$mezera_mezi_body_Y_leva+$vyska/11);
     imagefilledrectangle ($im, (20+($k * $sirka/$dnu)), $vyska, (26+($k * $sirka/$dnu)),  $y_bod["SP"] ,$kanal_SP);
     imagefilledrectangle ($im, (13+($k * $sirka/$dnu)), $vyska, (19+($k * $sirka/$dnu)),  $y_bod["FA"] ,$kanal_FA);
     @$souhrn["FA"]+= $ddata[$k]["FA"];
     @$souhrn["SP"]+= $ddata[$k]["SP"];
  
  
  }
  */
?>
    new Morris.Bar({
      element: '<?php echo $id_div; ?>',
      data: [
        <?php
          for($k=1;$k<=sizeof($ddata);$k++){
            echo "{ x: '".$k."' , a: ".(isset($ddata[$k]['SP']) ? $ddata[$k]['SP'] : 0).", b: ".(isset($ddata[$k]['FA']) ? $ddata[$k]['FA'] : 0)." },";
          } 
          $k = 1;
        ?>
      ],
      xkey: 'x',
      ykeys: ['a', 'b'],
      xmax: <?php echo $dnu; ?>,
      ymax: <?php echo $max_zad; ?>,
      labels: ['Spotřeba', 'Výroba'],
      barColors: ['#5058AB', '#14A0C1'],
      gridTextSize: 11,
      hideHover: 'auto',
      resize: false
    });
<?php 
  }
?>