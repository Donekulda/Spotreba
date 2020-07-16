<?php
$base = dirname(dirname(dirname(__FILE__)));
require_once($base."/config.inc.php");
require_once($base."/functions.inc.php");

$plugins = dirname(dirname(__FILE__));
  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
   
require_once $plugins.'/PHPMailer/src/Exception.php';
require_once $plugins.'/PHPMailer/src/PHPMailer.php';
require_once $plugins.'/PHPMailer/src/SMTP.php';


$SQL = "SELECT ip_id, email FROM month_update WHERE active = 1";
if($result = $conn->query($SQL)){
    while($row = $result->fetch_assoc()){
        $sql_month = "SELECT MONTH(FROM_UNIXTIME(datum)) as m, SUM(PL1) as PL1, SUM(PL2) as PL2, SUM(PL3) as PL3, SUM(FA1) as FA1, SUM(FA2) as FA2, SUM(FA3) as FA3 FROM data
                     WHERE MONTH(FROM_UNIXTIME(datum))=" . (date("n")-1) . " AND ip_id = " . $row['ip_id'] . " GROUP BY m";
        if($result_m = $conn->query($sql_month)){
            while($row_m = $result_m->fetch_assoc()){
                print_r($row_m);
                //System_mail_plus($row['email'], $jmeno, $prijmeni, 1, "Měsíční záznam - Aeko s.r.o.", ["%nick"=>$nick, "%heslo"=>$heslo, "%jmeno"=>$jmeno, "%prijmeni"=>$prijmeni]);    
            }
        }else
            echo $conn->error;
    }
}else
    echo $conn->error;

echo "Ahoj";

?>