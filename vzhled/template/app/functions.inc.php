<?php
//Funkce pro vrácení konkrétní hodnoty z konkrétní tabulky
  function GetVariable($id, $table, $category){
    if(isset($_SESSION['connection'])){
    	$sql = "SELECT ".$category." FROM ".$table." WHERE id=?"; 
		if($stmt = $_SESSION['connection']->prepare($sql)){
		    $stmt->bind_param('d', $id);
		    if($stmt->execute()){
        		$stmt->store_result();
		        if($stmt->num_rows > 0){ 
			    	$stmt->bind_result($value);
		        	$stmt->fetch();
		        	return $value;   
		        }
		    }else{
		        echo $stmt->error;
		        return null;
		    }
	    }
		echo $_SESSION['connection']->error;
		return null;
    }
    return null;  
  }
    
//Funkce pro vrácení id z konkrétní tabulky
  function GetId($table, $category, $value){
    if(isset($_SESSION['connection'])){
      $stmt = $_SESSION['connection']->prepare("SELECT id FROM ".$table." WHERE ".$category."=? LIMIT 1");
      $stmt->bind_param("s", $value);
      if($stmt->execute()){
        $stmt->store_result();
        if($stmt->num_rows > 0){ 
	        $stmt->bind_result($id);
          $stmt->fetch();
          return $id;   
        }
      }else{
		echo $_SESSION['connection']->error;
      }
    
      return null; 
    }
    return null;  
  }
  
//Funkce pro změnění nějakého konkrétního sringu např. Jmena, ip, adresy atd.  
  function ChangeString($id, $table, $category, $value){
    $conn = $_SESSION['connection'];
    if(isset($_SESSION['connection'])){
      $sql = "UPDATE ".$table." SET ".$category." = ? WHERE id=?";
      if ($stmt = $conn->prepare($sql)){
        $stmt->bind_param('sd', $value, $id);
          if($stmt->execute()){
            return true;
          }else{
            echo $stmt->error;
            return false;
          }
      }

      /*
      $sql = "UPDATE ".$table." SET ".$category."= ".$value." WHERE id=".$id;

      if ($_SESSION['connection']->query($sql) === TRUE) {
        return true;
      } else {
        echo "Error updating record: " . $_SESSION['connection']->error;
        return false;
      }
      */
    }
    return false;  
  }

//Funkce ro zjištění jestli daná hodnota už není použita. Využito pro kontrolu emailu a nicku  
  function DoesExist($table ,$category, $value){
    if(isset($_SESSION['connection'])){
      $sql = "SELECT ".$category." FROM ".$table;
      $result = $_SESSION['connection']->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          if($row[$category] == $value){
            return true;
          }
        }
        return false;
      }
      return false;  
    }
    header("location: ../error.php"); 
  }
    
//Funkce pro zkontrolování zalogování na stránce
  function IsLogged($title){
    global $adresa;
    $login_url = $adresa."login/";
    if(isset($_SESSION['connection'])){
      $conn = $_SESSION['connection'];
      if(!isset($_SESSION['id']) && $title != "Přihlášení"){
        if(isset($_COOKIE['login-cookie'])){
          $cookie = $_COOKIE['login-cookie'];
          $content = base64_decode ($cookie);
          list($myID, $hashed_password) = explode (':', $content);
      
          $sql = "SELECT * FROM uzivatele WHERE id = '$myID'";
          if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
           
            // If result matched $myusername and $mypassword, table row must be 1 row	
            if(md5($row['heslo'], substr(md5($row['heslo']), 1, 9)) == $hashed_password) {
              $_SESSION['id'] = $row['id'];
              header("Refresh:0");
            }
          }else
            die($conn->error);
        }
        header("location: ".$login_url);
      }
      /*
      if(!isset($_SESSION['id']) && $title == "Přihlášení"){
        header("location: ./index.php");
      }
      */ 
      return true;  
    }else
      return false;
  }

//Funkce pro vygenerování náodného klíče  
  function GenerateKey($delka){
    $array = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9), ["!", "@"]);
    $key = "";
    for($i = 0; $i < $delka; $i++){
      $key .= $array{mt_rand(0,sizeof($array)-1)};  
    }
    return $key;      
  }
       
  function redirect($url){
    if (headers_sent()){
      die('<script type="text/javascript">window.location=\''.$url.'\';</script‌​>');
    }else{
      header('Location: ' . $url);
      die();
    }    
  }
  
  function vypis($text){
    if ($text != null) {
      return $text;
    } else {
      return "-";
    }
  }

//vytvoří log  
  function CreateLog($uzivatelId, $log, $ovlivnenyId){
    if(isset($_SESSION['connection'])){
      $opravneni = GetVariable($uzivatelId, "uzivatele", "opravneni"); //Dostaneme hodnotu opravneni
      $celJm = GetVariable($uzivatelId, "uzivatele", "Jmeno")." ".GetVariable($uzivatelId, "uzivatele", "Prijmeni");  //Dostaneme celé jmeno daného uživatele z důvodu pozdějších jmen,aby kdyby někdo se dostal do admin účtu nemohl změnit uživatelovo jméno z logu
      $sql = "INSERT INTO `logs`(`uzivatelId`, `ovlivnenyId`, `opravneni`, `druh-logu`, `Jm-a-Pr`) VALUES ($uzivatelId, $ovlivnenyId, $opravneni, $log, $celJm)";  
    }
  }
  
//funkce pro získání textu z predlohy
  function GetPredlohaText($predloha, $array){
    if(isset($_SESSION['connection'])){
      $text = GetVariable($predloha, "predlohy", "Obsah");

      $result = str_replace(array_keys($array), array_values($array), $text);
      return $result; 
    } 
    return null;
  }


// Funkce pro odeslání systémové zprávy
  function System_mail($komu_mail, $komu_jmeno, $komu_prijm, $predloha, $Nazev, $array){
    if(isset($_SESSION['connection'])){
      $text = GetPredlohaText($predloha, $array);

      $mail = new PHPMailer\PHPMailer\PHPMailer();

      try {
        //Server settings
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->setLanguage('cs', '../plugins/PHPMailer/language/');
    
        //Recipients
        $mail->setFrom('noreply@solarnivyroba.cz', 'NoReply');
        $mail->addAddress($komu_mail, $komu_jmeno." ".$komu_prijm);       
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $Nazev;
        $mail->Body    = $text;
     
        // SMTP parametrs
        $mail->isSMTP();

        /* SMTP server address. */
        $mail->Host = 'mail.vas-hosting.cz';
        
        //Debugger
        //$mail->SMTPDebug = 4;

        /* Use SMTP authentication. */
        $mail->SMTPAuth = true;
        $mail->AuthType = 'LOGIN';

        /* Set the encryption system. */
        $mail->SMTPSecure = "ssl";

        /* SMTP authentication username. */
        $mail->Username = 'noreply@solarnivyroba.cz';

        /* SMTP authentication password. */
        $mail->Password = 'HfHFhWaUh8oKiISB';

        /* Set the SMTP port. */
        $mail->Port = 465;
        
        $mail->send();

        //echo 'Zpráva byla odeslána';
      } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: http://spotreba.solarnivyroba.cz/error.php");
      }
    }else{
      echo $_SESSION['connection']->error;  
    }
  }

//Funkce pro převedení číselné hpdnoty měsíce na slovní typ v češtině
  function cesky_mesic($mesic_int) {
    static $mesice = array(1 => 'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec');
    return $mesice[$mesic_int];
  }


//Funkce pro převedení měsíce v čětině na číselné datum
  function cesky_mesic_int($mesic) {
    static $mesice = array(1 => "Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec");
    return array_search($mesic,$mesice);
  }

//Funkce která nám vrátí čas ve formátu H, m, s
  function GetCas($cas){
    $array = array(
      "Hour" => (int)date('H', $cas),
      "minute" => (int)date('i', $cas),
      "secund" => (int)date('s', $cas)
    );
    
    return $array["Hour"].", ".$array["minute"].", ".$array["secund"];
  }

  function printSuccess($text){
    echo "<div class=\"alert alert-success\" role=\"alert\">";
    echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">";
    echo "    <span aria-hidden=\"true\">&times;</span>";
    echo "  </button>";
    echo "  <div class=\"d-flex align-items-center justify-content-start\">";
    echo "    <i class=\"icon ion-ios-checkmark alert-icon tx-24 mg-t-5 mg-xs-t-0\"></i>";
    echo "    <span><strong>Úspěch!</strong> ". $text ."</span>";
    echo "  </div><!-- d-flex -->";
    echo "</div><!-- alert -->";
  }

  function printWarning($text){
    echo "<div class=\"alert alert-warning\" role=\"alert\">";
    echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">";
    echo "    <span aria-hidden=\"true\">&times;</span>";
    echo "  </button>";
    echo "  <div class=\"d-flex align-items-center justify-content-start\">";
    echo "    <i class=\"icon ion-ios-checkmark alert-icon tx-24 mg-t-5 mg-xs-t-0\"></i>";
    echo "    <span><strong>Upozornění!</strong> ". $text ."</span>";
    echo "  </div><!-- d-flex -->";
    echo "</div><!-- alert -->";
  }

  function printError($text){
    echo "<div class=\"alert alert-danger\" role=\"alert\">";
    echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">";
    echo "    <span aria-hidden=\"true\">&times;</span>";
    echo "  </button>";
    echo "  <div class=\"d-flex align-items-center justify-content-start\">";
    echo "    <i class=\"icon ion-ios-checkmark alert-icon tx-24 mg-t-5 mg-xs-t-0\"></i>";
    echo "    <span><strong>Chyba!</strong> ". $text ."</span>";
    echo "  </div><!-- d-flex -->";
    echo "</div><!-- alert -->";
  }

  function printInfo($text){
    echo "<div class=\"alert alert-info\" role=\"alert\">";
    echo "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">";
    echo "    <span aria-hidden=\"true\">&times;</span>";
    echo "  </button>";
    echo "  <div class=\"d-flex align-items-center justify-content-start\">";
    echo "    <i class=\"icon ion-ios-checkmark alert-icon tx-24 mg-t-5 mg-xs-t-0\"></i>";
    echo "    <span><strong>Info!</strong> ". $text ."</span>";
    echo "  </div><!-- d-flex -->";
    echo "</div><!-- alert -->";
  }
?>