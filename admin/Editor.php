<?php
  session_start();
  //error_reporting(-1);
  //ini_set('display_errors', 'On');
  
  require_once "../config.inc.php";
  require_once "../functions.inc.php";;
  
  $title= "Nastavení";
  $subtitle = "Editor";
  
  if(isset($_GET['predloha'])){
    $_SESSION['predloha_id'] = $_GET['predloha'];
  }else{
    $_SESSION['predloha_id'] = 0;
  }
  
  // Insert record
  if(isset($_POST['submit'])){

    $nazev = $_POST['nazev'];
    $obsah = $_POST['obsah'];

    if($nazev != ''){
      if($_SESSION['predloha_id'] > 0){
        $sql = "UPDATE predlohy SET Nazev='".$nazev."', obsah='".$obsah."' WHERE id=".$_SESSION['predloha_id'];
        if($conn->query($sql))
          echo "Změna byla provedena!";
        else
          echo $conn->error;
      }else{
        $sql = "INSERT INTO predlohy(Nazev,obsah) VALUES('".$nazev."','".$obsah."') ";
        if($conn->query($sql))
          echo "Změna byla provedena!";
        else
          echo $conn->error;
      }
    }else{
      echo "Musíš vyplnit Název!";
    }
  }
  
  if(!IsLogged($title))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") < 2){
    header("location: ../error.php");
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta name="description" content="Adminská sekce pro spotreba.solarnivyroba.cz">
    <meta name="author" content="Mikuláš Staněk">
  
    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">

    <!-- include libraries(jQuery, bootstrap) -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
  
  <!--
  <script src="http://spotreba.aeko.cz/upgrade/plugins/ckeditor/ckeditor.js" ></script>
  -->
  <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>
   
  <link rel="stylesheet" href="../vzhled/styl.css">
  <title>Admin sekce - <?php echo $title . "->" . $subtitle; ?></title>
  </head>
  <body>
    <div class="templatemo-flex-row">
  <?php 
    require_once "./menu.inc.php";
  ?>
  <div class="templatemo-top-nav-container">
      <div class="row">
          <nav class="templatemo-top-nav col-lg-12 col-md-12">
          <ul class="text-uppercase">
              <li><a href="./settings.php">Dokumenty</a></li>
              <li><a href="" class="active">Editor</a></li>
          </ul>  
          </nav> 
      </div>
  </div>  
  <div class="templatemo-content-container">
    <div class="templatemo-content-widget white-bg">
      <div class="paper">  
        <p style="color: red;">Ruce prič! pokud nevíš naco saháš!</p>
        <form id="predloha-form" method="get" action="">
          <select value="<? echo $_SESSION['predloha_id']; ?>" name="predloha">
            <option value="0" onclick="document.getElementById('predloha-form').submit();"> Vytvořit novou
              <?php
              $sql = "SELECT * from predlohy"; 
              $query = $_SESSION['connection']->query($sql);
          
              if ($query->num_rows > 0) {
                // output data of each row
                while($row = $query->fetch_assoc()) {
                  echo "<option onclick=\"document.getElementById('predloha-form').submit();\" value=\"".$row['id']."\"".($row['id']==$_SESSION['predloha_id']?" selected=\"selected\"":"").">".$row['id'].".".$row['Nazev'];
                }
              }
            ?>
          </select>
          <input type="submit" name="Zvolit" value="Zvolit">
        </form>

        <form method='post' action=''>
          Nazev : 
          <input type="text" name="nazev" style="width: 100%;" value=<?php echo "\"".($_SESSION['predloha_id']!=0?vypis(GetVariable($_SESSION['predloha_id'], "predlohy", "Nazev")):"")."\""; ?>><br>

          Obsah: 
          <textarea id='obsah' name='obsah' >
          <?php
            $sql = "SELECT Obsah from predlohy WHERE id=".$_SESSION['predloha_id']; 
            $query = $_SESSION['connection']->query($sql);
          
            if ($query->num_rows > 0) {
              // output data of each row
              while($row = $query->fetch_assoc()) {
                echo $row['Obsah'];
              }
            }else{
              echo $_SESSION['connection']->error; 
            }
          ?>
          </textarea><br>

          <input type="submit" name="submit" value="Submit">
        </form>
        
      </div>
    </div>
  </div>
  
    <script>

      // Initialize CKEditor
      CKEDITOR.replace('obsah',{
        width: "800px",
        height: "300px",
        customConfig: "../plugins/ckeditor/config.js"
	      //filebrowserBrowseUrl: '../plugins/ckfinder/ckfinder.html',
	      //filebrowserUploadUrl: '../plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
      }); 

    </script>
<?php
  require_once "./footer.inc.php";
?>