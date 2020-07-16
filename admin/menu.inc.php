<div class="templatemo-sidebar">
  <?php
    if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") > 1){
  ?>
    <!-- Search box -->
    <form class="templatemo-search-form" role="search">
      <div class="input-group">
          <button type="submit" class="fa fa-search"></button>
          <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">           
      </div>
    </form>
    <nav class="templatemo-left-nav">          
      <ul>
        <li><a href= <?php echo '"'.$adresa.'/app"'; ?> >Zpět</a>
        <!--[if IE]>Prozatim Nepodstatná stránka
        <li><a class="<?php if($title == "Hlavní stránka"){echo "active";} ?>" href="./index.php">Home</a>
        <![endif]-->
        <li><a class="<?php if($title == "Seznam uživatelů"){echo "active";} ?>" href="./uzivatele.php">Uživatelé</a></li>
        <li><a class="<?php if($title == "Registrace uživatele"){echo "active";} ?>" href="./registrace.php">Registrace</a></li>
        <li><a class="<?php if($title == "Seznam ip adres"){echo "active";} ?>" href="./zarizeni.php">Zařízení</a></li>
        <li><a class="<?php if($title == "Seznam Wattrouterů"){echo "active";} ?>" href="./wattrouter_type_manager.php">Druhy Wattrouterů</a></li>
        <li><a class="<?php if($title == "Seznam logů"){echo "active";} ?>" href="./logs.php">Logy</a></li>
        <li><a class="<?php if($title == "Nastavení"){echo "active";} ?>" href="./settings.php">Nastavení</a></li>
        <li><a href="../app/logout.php"><img class="logout" src="../vzhled/obrazky/pokus.png" width="20" height="20" title="Odhlášení" alt="Odhlášení"> Odhlásit se</a></li>
      </ul>
    </nav>
  <?php 
    }
  ?>
</div>
<div class="templatemo-content col-1 light-gray-bg">
  <div class="templatemo-content-container">