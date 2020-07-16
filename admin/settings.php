<?php
  $title = "Nastavení";
  require_once "./header.inc.php";
  $infos = array();
  $errors = array();

  if(isset($_POST['sbmDocUpl'])) {
    $target_dir = "../docs/";
    
    if(isset($_FILES["GDPRDoc"]) && $_FILES["GDPRDoc"]['name'] != ""){
      $file_name = $_FILES['GDPRDoc']['name'];
      $file_size = $_FILES['GDPRDoc']['size'];
      $file_tmp = $_FILES['GDPRDoc']['tmp_name'];
      $file_type = $_FILES['GDPRDoc']['type'];
      @$file_ext=strtolower(end(explode('.',$_FILES['GDPRDoc']['name'])));
      $file_dir = "../GDPR/". basename($_FILES['GDPRDoc']['name']);
      
      $extensions= array("pdf","doc","docx", "zip", "rar");
      
      if(in_array($file_ext,$extensions)=== false){
        $errors[]="Tento typ souboru není povolen!";
      }
      
      if($file_size > 5242880) {
        $errors[]='Soubor může mýt maximálně 5 MB!';
      }
      
      if(empty($errors)==true) {
        move_uploaded_file($file_tmp,$file_dir);
        array_push($infos, "GDPR dokument byl pozmněněn souborem ".$file_name);
        ChangeString(1, "Dokumenty", "file_name", $file_name);
      }
    }
    
    if(isset($_FILES["HelpDoc"]) && $_FILES["HelpDoc"]['name'] != ""){
      $file_name = $_FILES['HelpDoc']['name'];
      $file_size = $_FILES['HelpDoc']['size'];
      $file_tmp = $_FILES['HelpDoc']['tmp_name'];
      $file_type = $_FILES['HelpDoc']['type'];
      @$file_ext=strtolower(end(explode('.',$_FILES['HelpDoc']['name'])));
      $file_dir = $target_dir . basename($_FILES['HelpDoc']['name']);
      
      $extensions= array("pdf","doc","docx", "zip", "rar");
      
      if(in_array($file_ext,$extensions)=== false){
        $errors[]="Tento typ souboru není povolen!";
      }
      
      if($file_size > 5242880) {
        $errors[]='Soubor může mýt maximálně 5 MB!';
      }
      
      if(empty($errors)==true) {
        move_uploaded_file($file_tmp,$file_dir);
        array_push($infos, "Help dokument byl pozmněněn souborem ".$file_name);
        ChangeString(3, "Dokumenty", "file_name", $file_name);
      }
    }
    
    if(isset($_FILES["KontaktDoc"]) && $_FILES["KontaktDoc"]['name'] != ""){
      $file_name = $_FILES['KontaktDoc']['name'];
      $file_size = $_FILES['KontaktDoc']['size'];
      $file_tmp = $_FILES['KontaktDoc']['tmp_name'];
      $file_type = $_FILES['KontaktDoc']['type'];
      @$file_ext=strtolower(end(explode('.',$_FILES['KontaktDoc']['name'])));
      $file_dir = $target_dir . basename($_FILES['KontaktDoc']['name']);
      
      $extensions= array("pdf","doc","docx", "zip", "rar");
      
      if(in_array($file_ext,$extensions)=== false){
        $errors[]="Tento typ souboru není povolen!";
      }
      
      if($file_size > 5242880) {
        $errors[]='Soubor může mýt maximálně 5 MB!';
      }
      
      if(empty($errors)==true) {
        move_uploaded_file($file_tmp,$file_dir);
        array_push($infos, "Dokument s Kontakty byl pozmněněn souborem ".$file_name);
        ChangeString(4, "Dokumenty", "file_name", $file_name);
      }
    }
    
    if(isset($_FILES["TermsDoc"]) && $_FILES["TermsDoc"]['name'] != ""){
      $file_name = $_FILES['TermsDoc']['name'];
      $file_size = $_FILES['TermsDoc']['size'];
      $file_tmp = $_FILES['TermsDoc']['tmp_name'];
      $file_type = $_FILES['TermsDoc']['type'];
      @$file_ext=strtolower(end(explode('.',$_FILES['TermsDoc']['name'])));
      $file_dir = $target_dir . basename($_FILES['TermsDoc']['name']);
      
      $extensions= array("pdf","doc","docx", "zip", "rar");
      
      if(in_array($file_ext,$extensions)=== false){
        $errors[]="Tento typ souboru není povolen!";
      }
      
      if($file_size > 5242880) {
        $errors[]='Soubor může mýt maximálně 5 MB!';
      }
      
      if(empty($errors)==true) {
        move_uploaded_file($file_tmp,$file_dir);
        array_push($infos, "Dokument podmínek použití byl pozmněněn souborem ".$file_name);
        ChangeString(2, "Dokumenty", "file_name", $file_name);
      }
    }
  }

?>
    <div class="templatemo-flex-row flex-content-row">
        <?php  if (count($errors) > 0) { ?>
        <div class="templatemo-content-widget orange-bg col-2">
            <i class="fa fa-times"></i>
            <h2 class="text-uppercase">Error</h2>
            <h3 class="text-uppercase margin-bottom-10">výpis errorů</h3>
            <?php foreach ($errors as $error) : ?>
            <p><?php echo $error; ?></p>
            <?php endforeach ?>
        </div>
            
        <?php  } 
        if (count($infos) > 0) { ?>
        <div class="templatemo-content-widget blue-bg col-2">
            <i class="fa fa-times"></i>
            <h2 class="text-uppercase">Info</h2>
            <?php foreach ($infos as $info) : ?>
            <p><?php echo $info; ?></p>
            <?php endforeach ?>
        </div>
            
        <?php } ?>
    </div>
    <div class="templatemo-top-nav-container">
        <div class="row">
            <nav class="templatemo-top-nav col-lg-12 col-md-12">
            <ul class="text-uppercase">
                <li><a href="" onclick="alert('Ruce prič! pokud nevíš co děláš.');" class="active">Dokumenty</a></li>
                <li><a href="./Editor.php">Editor</a></li>
            </ul>  
            </nav> 
        </div>
    </div>       
    <div class="templatemo-content-container">
        <div class="templatemo-content-widget white-bg">
            <h2 class="margin-bottom-10">Nastavení</h2>
            <p>Zde se nahrávají dokumenty pro různé podskupiny.</p>
            <form action="#" class="templatemo-login-form" method="post" enctype="multipart/form-data" name="docUpload">
                <div class="col-lg-12">
                    <label class="control-label templatemo-block">Help dokument => <a href="../docs/<?php echo GetVariable(3, "Dokumenty", "file_name"); ?>">klikni pro stažení</a></label> 
                    <input type="file" name="HelpDoc" id="HelpDoc" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" accept=".doc, .docx, application/msword, application/pdf">
                    <p>Maximální velikost souboru je 5 MB.</p>    
                </div>

                <div class="col-lg-12">
                    <label class="control-label templatemo-block">Podmínky použití => <a href="../docs/<?php echo GetVariable(2, "Dokumenty", "file_name"); ?>">klikni pro stažení</a></label> 
                    <input type="file" name="TermsDoc" id="TermsDoc" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" accept=".doc, .docx, application/msword, application/pdf">
                    <p>Maximální velikost souboru je 5 MB.</p>                  
                </div>

                <div class="col-lg-12">
                    <label class="control-label templatemo-block">GDPR => <a href="../GDPR/<?php echo GetVariable(1, "Dokumenty", "file_name"); ?>">klikni pro stažení</a></label> 
                    <input type="file" name="GDPRDoc" id="GDPRDoc" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" accept=".doc, .docx, application/msword, application/pdf">
                    <p>Maximální velikost souboru je 5 MB.</p>    
                </div>

                <div class="col-lg-12">
                    <label class="control-label templatemo-block">Kontakt => <a href="../docs/<?php echo GetVariable(4, "Dokumenty", "file_name"); ?>">klikni pro stažení</a></label> 
                    <input type="file" name="KontaktDoc" id="KontaktDoc" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" accept=".doc, .docx, application/msword, application/pdf">
                    <p>Maximální velikost souboru je 5 MB.</p>    
                </div>

                <div class="form-group text-right">
                    <button type="submit" name="sbmDocUpl" class="templatemo-blue-button">Update</button>
                    <button type="reset" class="templatemo-white-button">Reset</button>
                </div>                           
            </form>
        </div>
     </div>

    <!-- JS -->
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>        <!-- jQuery -->
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>  <!-- http://markusslima.github.io/bootstrap-filestyle/ -->
    <script type="text/javascript" src="js/templatemo-script.js"></script>        <!-- Templatemo Script -->

<?php
  require_once "./footer.inc.php";
?>