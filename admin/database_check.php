<?php
  session_start();
  error_reporting(-1);
  $base = dirname((dirname(__FILE__));
  require_once($base."/config.inc.php");
  require_once($base."/functions.inc.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
        
    require_once $base.'/plugins/PHPMailer/src/Exception.php';
    require_once $base.'/plugins/PHPMailer/src/PHPMailer.php';
    require_once $base.'/plugins/PHPMailer/src/SMTP.php';

    $errorcount = 0;
    $errors = array();

    function mysqli_result($res,$row=0,$col=0){ 
        $numrows = mysqli_num_rows($res); 
        if ($numrows && $row <= ($numrows-1) && $row >=0){
            mysqli_data_seek($res,$row);
            $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
            if (isset($resrow[$col])){
                return $resrow[$col];
            }
        }
        return false;
    } 

    $arr = array("data","ip_adresy","uzivatele");
    foreach ($arr as &$value) {
        if(!$querry = $conn->query("SELECT * FROM ".$value." LIMIT 1")){
            $error = $conn->error;
            array_push($errors, $error);
            $errorcount++;
        }
        
    }
    
    $sql = "INSERT INTO server_check (errors)
    VALUES (".$errorcount.")";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    if($errorcount > 0){
        $error = "";
        foreach ($errors as &$value) {
            $error .= (string)$value."<br>";
        }

        $cas = StrFTime("%d/%m/%Y %H:%M:%S", Time());
        System_mail($system_mail, "Info", "Aeko", 6, "Nastala chyba v databázi!", ["%cas"=>$cas, "%error"=>$error]); 
        System_mail("mstanek@aeko.cz", "Mikuláš", "Staněk", 6, "Nastala chyba v databázi!", ["%cas"=>$cas, "%error"=>$error]);     
    }

    $conn->close();
?>