<?php
  session_start();
  error_reporting(-1);
  ini_set('display_errors', 'On');
  
  $title = "Hlavní stránka";
  $subtitle = "Roční vývoj";
   
  require_once "../config.inc.php";
  require_once "../functions.inc.php"; 
    
  if(!IsLogged($title)){
    header("location: ./error.php");
  }

  if(count($_COOKIE) <= 0) {
    if(isset($_SESSION['cookie_error_count']) && $_SESSION['cookie_error_count']==true){ 
      die ("Povolte v nastavení Cookies! A refreshnete stránku! PS: může se stát že máte cookies povolené tak projistotu kdyžtak také refreshněte stránku!");
    }
    $_SESSION['cookie_error'] = true;
    header("Refresh:0");
  }else{
    $_SESSION['cookie_error'] = false;
  }

  require_once "./header.inc.php";
  
  /*
  if(isset($_GET["mesic2"])) $_SESSION["mesic2"] = $_GET["mesic2"]*1;
  if(isset($_GET["rok"])) $_SESSION["rok2"] = $_GET["rok"]*1;
  */
  
  if(isset($_GET["rok"])){
    $_SESSION["rok2"] = $_GET["rok"]; // mesic/den/rok
  }else{
    $_SESSION["rok2"] = date("Y");
  }

  $max=0;

  $query = mysqli_query($conn,"SELECT MONTH(FROM_UNIXTIME(datum)) as m, SUM(PL1+PL2+PL3) as SPSpol, SUM(FA1 + FA2 + FA3) as FASpol FROM data WHERE ip_id=".$_SESSION['show_ip']." AND YEAR(FROM_UNIXTIME(datum)) = ".$_SESSION["rok2"]." GROUP BY m") or die(mysql_error());
  while($radek=mysqli_fetch_assoc($query))
    $max = max($max,$radek["FASpol"],$radek["SPSpol"]);

  $max /= 60;
  $max += 5000; 
  $max = round($max, -1);
?>

      <div class="am-pagetitle">
          <h5 class="am-title">Roční vývoj</h5>
          <?php include_once "select-Ip.inc.php"; ?>
          <!-- search-bar -->
      </div>
      <!-- am-pagetitle -->

      <div class="am-pagebody">
          <div class="row row-sm">
              <div class="col-xl-6">
                  <div class="card pd-20 pd-sm-40">
                    <h4 style="text-align:center; color:black;"><strong>Fáze 1<?php $nazev = GetVariable($_SESSION['show_ip'], "ip_adresy", "Nazev_Faze1"); echo (($nazev != "")?(" - ".$nazev):""); ?></strong></h4>
                    <img style="width: 100%;"  src="http://spotreba.solarnivyroba.cz/plugins/grafy/grafr.php?faze=1&amp;max=<?=$max?>&amp;rok=<?=$_SESSION["rok2"]?>&amp;pVstupu=<?=$pocetVstupu?>&amp;delitel=<?=$delic?>">  
                  </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-xl-6">
                  <div class="card pd-20 pd-sm-40">
                    <h4 style="text-align:center; color:black;"><strong>Fáze 2<?php $nazev = GetVariable($_SESSION['show_ip'], "ip_adresy", "Nazev_Faze2");  echo (($nazev != "")?(" - ".$nazev):""); ?></strong></h4>
                    <img style="width: 100%;"  src="http://spotreba.solarnivyroba.cz/plugins/grafy/grafr.php?faze=2&amp;max=<?=$max?>&amp;rok=<?=$_SESSION["rok2"]?>&amp;pVstupu=<?=$pocetVstupu?>&amp;delitel=<?=$delic?>">    
                  </div><!-- card -->
              </div><!-- col-6 -->
          </div><!-- row -->
          
          <div class="row row-sm mg-t-15 mg-sm-t-20">
              <div class="col-xl-6">
                  <div class="card pd-20 pd-sm-40">
                    <h4 style="text-align:center; color:black;"><strong>Fáze 3<?php $nazev = GetVariable($_SESSION['show_ip'], "ip_adresy", "Nazev_Faze3");  echo (($nazev != "")?(" - ".$nazev):""); ?></strong></h4>
                    <img style="width: 100%;"  src="http://spotreba.solarnivyroba.cz/plugins/grafy/grafr.php?faze=3&amp;max=<?=$max?>&amp;rok=<?=$_SESSION["rok2"]?>&amp;pVstupu=<?=$pocetVstupu?>&amp;delitel=<?=$delic?>">   
                  </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-xl-6">
                  <div class="card pd-20 pd-sm-40">
                    <h4 style="text-align:center; color:black;"><strong>Součet fází</strong></h4>  
                    <img style="width: 100%;"  src="http://spotreba.solarnivyroba.cz/plugins/grafy/grafr.php?faze=-1&amp;max=<?=$max?>&amp;rok=<?=$_SESSION["rok2"]?>&amp;pVstupu=<?=$pocetVstupu?>&amp;delitel=<?=$delic?>">  
                  </div><!-- card -->
              </div><!-- col-6 -->
          </div><!-- row -->
      </div>
      <!-- am-pagebody -->
      
      <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
      <script src="../lib/YearPicker/yearpicker.js"></script>
      <script>
        $(document).ready(function() {
            $("input.yearpicker").yearpicker({
            year: <?php if(isset($_GET['rok'])){ echo $_GET['rok']; }else{ echo date("Y"); } ?>,
            startYear: 2019,
            endYear: 2050,
            hide: function (){
                document.getElementById("yearForm").submit();
                console.log("hoh");
            }
            });
        });
      </script>
  
    <?php 
      /*include_once "./grafm.php";
    ?>
    <script>
     window.onload = function () {
      <?php
        $faze1 = CreateGraf("Faze1", 1, $_SESSION["mesic2"]."-".$_SESSION["rok2"], $max);
        $faze2 = CreateGraf("Faze2", 2, $_SESSION["mesic2"]."-".$_SESSION["rok2"], $max);
        $faze3 = CreateGraf("Faze3", 3, $_SESSION["mesic2"]."-".$_SESSION["rok2"], $max);
        $faze0 = CreateGraf("FazeSou", -1, $_SESSION["mesic2"]."-".$_SESSION["rok2"], $max);
      ?>
     }
     </script>
<?php
*/
  include_once "footer.inc.php";
?>
