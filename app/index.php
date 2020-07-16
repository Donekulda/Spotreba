<?php 
    session_start();
    error_reporting(-1);
    ini_set('display_errors', 'On');
    $title = "Hlavní stránka";
    $subtitle = "Aktuální vývoj";
    
    require_once "../config.inc.php";
    require_once "../functions.inc.php"; 
  
    if(count($_COOKIE) <= 0) {
      if(isset($_SESSION['cookie_error_count']) && $_SESSION['cookie_error_count']==true){ 
        die ("Povolte v nastavení Cookies! A refreshnete stránku! PS: může se stát že máte cookies povolené tak projistotu kdyžtak také refreshněte stránku!");
      }
      $_SESSION['cookie_error'] = true;
      header("Refresh:0");
    }else{
      $_SESSION['cookie_error'] = false;
    }
     
    if(!IsLogged($title)){
      header("location: ./error.php");
    }
    
    $inform_text = "Dnešní";

    include_once "header.inc.php";

    $timestamp = mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"))-60;
    $od = $timestamp-6*3600;
  
    $sql = "SELECT MAX(SP1 + SP2 + SP3) SPSpol, MAX(FA1 + FA2 + FA3) FASpol from data WHERE ip_id=".$_SESSION['show_ip']." AND (datum BETWEEN ".$od." AND ".$timestamp.")"; 
    $query = $_SESSION['connection']->query($sql);
    $data = mysqli_fetch_assoc($query);
    
    if($data){
        /*
        $max = max($data["SPSpol"],$data["FASpol"])*750;
        $max += 25; 
        $max = round($max, -3);
        $max += 2000;
        */
        $max = GetVariable($_SESSION['show_ip'], "ip_adresy", "Vykon") * 1000;
        $max += 2000;
    }else{
        $max = max(0,0,0,0,0,0)*1000;
    }

?>
        <div class="am-pagetitle">
            <h5 class="am-title">Aktuální vývoj</h5>
            <?php include_once "./select-Ip.inc.php"; ?>
        </div>
        <!-- am-pagetitle -->

        <div class="am-pagebody">
            <div class="row row-sm">
                <div class="col-xl-6">
                    <div class="card pd-20 pd-sm-40">
                        <div id="Faze1" class="wd-100p ht-200 ht-sm-300"></div>
                    </div><!-- card -->
                </div><!-- col-6 -->
                <div class="col-xl-6">
                    <div class="card pd-20 pd-sm-40">
                        <div id="Faze2" class="wd-100p ht-200 ht-sm-300"></div> 
                    </div><!-- card -->
                </div><!-- col-6 -->
            </div><!-- row -->
            
            <div class="row row-sm mg-t-15 mg-sm-t-20">
                <div class="col-xl-6">
                    <div class="card pd-20 pd-sm-40">
                        <div id="Faze3" class="wd-100p ht-200 ht-sm-300"></div>
                    </div><!-- card -->
                </div><!-- col-6 -->
                <div class="col-xl-6">
                    <div class="card pd-20 pd-sm-40">
                    <div id="FazeSou" class="wd-100p ht-200 ht-sm-300"></div>
                    </div><!-- card -->
                </div><!-- col-6 -->
            </div><!-- row -->
        </div>
        <!-- am-pagebody -->
        
    
    <?php 
        include_once "./graf.php";
    ?>
    <script>

     window.onload = function () {
        <?php 
            $faze1 = CreateGraf("Faze1", 1, 0, $max, GetVariable($_SESSION['show_ip'], "ip_adresy", "Nazev_Faze1"));
            $faze2 = CreateGraf("Faze2", 2, 0, $max, GetVariable($_SESSION['show_ip'], "ip_adresy", "Nazev_Faze2"));
            $faze3 = CreateGraf("Faze3", 3, 0, $max, GetVariable($_SESSION['show_ip'], "ip_adresy", "Nazev_Faze3"));
            $faze0 = CreateGraf("FazeSou", -1, 0, $max, "");
        ?>
     }
     </script>
<?php
    include_once "footer.inc.php";
?>