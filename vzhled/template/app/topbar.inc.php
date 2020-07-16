<div class="am-header">
  <div class="am-header-left">
    <a id="naviconLeft" href="" class="am-navicon d-none d-lg-flex"><i class="icon ion-navicon-round"></i></a>
    <a id="naviconLeftMobile" href="" class="am-navicon d-lg-none"><i class="icon ion-navicon-round"></i></a>
    <a href="index.php" class="am-logo">Aeko</span></a>
  </div><!-- am-header-left -->

  <?php
    if(GetVariable($_SESSION['id'], "uzivatele", "img") == 0){
      $img = $adresa."vzhled/obrazky/profil/".$_SESSION['opravneni'].".png";
    }else{
      $img = "../img/profil/".$_SESSION['id'];
    }
  ?>
  <div class="am-header-right">
    <div class="dropdown dropdown-profile">
      <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
        <img src="<?php echo $img; ?>" class="wd-32 rounded-circle" alt="">
        <span class="logged-name"><span class="hidden-xs-down"><?php echo $_SESSION['jmeno']." ".$_SESSION['prijmeni']; ?></span> <i class="fa fa-angle-down mg-l-3"></i></span>
      </a>
      <div class="dropdown-menu wd-200">
        <ul class="list-unstyled user-profile-nav">
          <?php if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") > 0 && isset($_SESSION['id'])){ ?>
          <li><a href="./profil.php"><i class="icon ion-ios-person-outline"></i> Upravit Profil</a></li>
          <?php }
            if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") > 1 && isset($_SESSION['id'])){
          ?>
          <li><a href=""><i class="icon ion-ios-gear-outline"></i> Nastavení</a></li>
            <?php } ?>
          <li><a href="./logout.php"><i class="icon ion-power"></i> Odhlásit se</a></li>
        </ul>
      </div><!-- dropdown-menu -->
    </div><!-- dropdown -->
  </div><!-- am-header-right -->
</div><!-- am-header -->