
    <div class="am-sideleft">
    <?php
      if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") > -1 && isset($_SESSION['id'])){
    ?>
      <ul class="nav am-sideleft-tab">
        <li class="nav-item" style="width: 240px;">
          <a href="#mainMenu" class="nav-link active"><i class="icon ion-ios-home-outline tx-24"></i></a>
        </li>

        <!--
        <li class="nav-item" style="width: 120px;">
          <a href="#settingMenu" class="nav-link"><i class="icon ion-ios-gear-outline tx-24"></i></a>
        </li>
        -->

      </ul>
      <div class="tab-content">
        <div id="mainMenu" class="tab-pane active">
          <ul class="nav am-sideleft-menu">
            <li class="nav-item">
              <a href="" class="nav-link with-sub <?php echo ($title == "Hlavní stránka")?"active show-sub":""; ?>">
                <i class="icon ion-ios-home-outline"></i>
                <span>Úvod</span>
              </a>
              <ul class="nav-sub">
                <li class="nav-item"><a href="index.php" class="nav-link <?php echo ($subtitle == "Aktuální vývoj")?"active":""; ?>">Aktuální vývoj</a></li>
                <li class="nav-item"><a href="index2.php" class="nav-link <?php echo ($subtitle == "Denní vývoj")?"active":""; ?>">Denní vývoj</a></li>
                <li class="nav-item"><a href="index3.php" class="nav-link <?php echo ($subtitle == "Měsíční vývoj")?"active":""; ?>">Měsíční vývoj</a></li>
                <li class="nav-item"><a href="index4.php" class="nav-link <?php echo ($subtitle == "Roční vývoj")?"active":""; ?>">Roční vývoj</a></li>
              </ul>
            </li><!-- nav-item -->
            <?php if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") > 1 && isset($_SESSION['id'])){ ?>
            <li class="nav-item">
              <a href="<?php echo $adresa; ?>admin/" class="nav-link">
                <i class="icon ion-settings"></i>
                <span>Admin</span>
              </a>
            </li><!-- nav-item -->
            <?php } 
              if($title == "Hlavní stránka"){
                if($subtitle == "Denní vývoj"){
            ?>
            <form id="datum" method="get" action="">
              <div class="bd pd-15 mg-t-15">
                <h6 class="tx-13 tx-normal tx-gray-800">Nastavení data dne</h6>
                <div class="input-group">
                  <style>
                  </style>
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input type="text" name="date" class="form-control fc-datepicker" placeholder="DD-MM-YYYY" <?php if(isset($_GET['date'])){ echo "value='".$_GET['date']."'"; }else{ echo "value='".date("d").".".date("n").".".date("Y")."'"; } ?>>
                </div>
              </div><!-- bd -->
            </form>

            <script>
              $('.fc-datepicker').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                maxDate: 0,
                minDate: new Date(2016, 1 - 1, 1),
                changeMonth: true,
                changeYear: true,
                showAnim: "drop",
                onSelect: function (){
                  document.getElementById("datum").submit();
                }
              });
            </script>

              <?php 
                }else if($subtitle == "Měsíční vývoj"){ 
              ?>

            <script src="../js/jquery.ui.monthpicker.min.js"></script>  

            <form id="mesice" method="get" action="">
              <div class="bd pd-15 mg-t-15">
                <h6 class="tx-13 tx-normal tx-gray-800">Nastavení data měsíce</h6>
                <div class="input-group">
                  <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                  <input type="text" class="form-control monthpicker" placeholder="MM-YYYY" name="date" <?php if(isset($_GET['date'])){ echo "value='".$_GET['date']."'"; }else{ echo "value='".cesky_mesic(date("n"))." ".date("Y")."'"; } ?>>
                </div>
              </div><!-- bd -->
            </form>

            <script>
              $('input.monthpicker').monthpicker({
                changeYear:true, 
                minDate: new Date(2016, 1 - 1, 1), 
                maxDate: 0,
                dateFormat: 'MM yy',
                yearSuffix: '',      // Additional text to append to the year in the month headers
                prevText: 'Před',   // Display text for previous month link
                nextText: 'Další',   // Display text for next month link
                monthNames: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'], // Names of months for drop-down and formatting
                monthNamesShort: ['Led', 'Ún', 'Břez', 'Dub', 'Květ', 'Červ', 'Čec', 'Srp', 'Zář', 'Říj', 'List', 'Pros'],
                onSelect: function (){
                  document.getElementById("mesice").submit();
                }
              });
            </script>
                <?php
                 }else if($subtitle == "Roční vývoj"){
             ?>
             
            <link rel="stylesheet" href="../lib/YearPicker/yearpicker.css" /> 
            
             <form id="yearForm" method="get" action="">
               <div class="bd pd-15 mg-t-15">
                 <h6 class="tx-13 tx-normal tx-gray-800">Nastavení data dne</h6>
                 <div class="input-group">
                   <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                   <input type="text" name="rok" class="yearpicker form-control" placeholder="YYYY" value="" />
                 </div>
               </div><!-- bd -->
             </form>
               <?php 
                 }
                } 
              ?>
        </div><!-- #mainMenu -->
        
        <!--<div id="settingMenu" class="tab-pane">
          <div class="pd-x-15">
            <label class="tx-uppercase tx-11 mg-t-10 tx-orange mg-b-15 tx-medium">Rychlé nastavení</label>
            
            <div class="bd pd-15">
              <h6 class="tx-13 tx-normal tx-gray-800">Vypočítávat výdělek</h6>
              <p class="tx-12">Get notified when someone else is trying to access your account.</p>
              <div class="toggle"></div>
            </div><!-- bd -->
        <!--    
            <div class="bd pd-15">
              <h6 class="tx-13 tx-normal tx-gray-800">Nastavení data</h6>
              <div class="input-group">
                <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
                <input type="text" class="form-control fc-datepicker" placeholder="MM/DD/YYYY">
              </div>
            </div><!-- bd -->

            <!-- <a href="#Settings" class="tx-uppercase tx-12 mg-t-10 tx-orange mg-b-15 tx-medium"><u>Rozšířenné nastavení   +</u></a> -->
          <!--</div>
        </div><!-- #settingMenu -->

      </div><!-- tab-content -->
      <?php }else{ ?>
        <div class="tab-content">
        <div id="mainMenu" class="tab-pane active">
          <ul class="nav am-sideleft-menu">
            <li class="nav-item">
              <a href="<?php echo $adresa; ?>login/" class="nav-link">
                <i class="fa fa-plug"></i>
                <span>Login</span>
              </a>
            </li><!-- nav-item -->
          </ul>
        </div><!-- #mainMenu -->
      </div><!-- tab-content -->
      <?php } ?>
    </div><!-- am-sideleft -->

