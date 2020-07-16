<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);

   require_once "./config.inc.php";
   require_once "./functions.inc.php";

   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
      
   require_once './plugins/PHPMailer/src/Exception.php';
   require_once './plugins/PHPMailer/src/PHPMailer.php';
   require_once './plugins/PHPMailer/src/SMTP.php';

    function wattrouter($ip, $port, $settings){
        //	$fp=@fsockopen($ip, 80, $errno, $errstr, 5);
        //	if($fp){
         //     fclose($fp);
         
        echo "http://".$ip.":".$port."/meas.xml<br>";
        $xml = simplexml_load_file("http://".$ip.":".$port."/meas.xml");
        if($xml){
            if(strpos($settings['PL1'], '[') !== false){
                $splitStr = explode("[", $settings['PL1']);
                $point = substr($splitStr[1], 0, -1);
                $array["PL1"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["PL1"] = (string)$xml->{$settings['PL1']};
            }

            if(strpos($settings['PL2'], '[') !== false){
                $splitStr = explode("[", $settings['PL2']);
                $point = substr($splitStr[1], 0, -1);
                $array["PL2"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["PL2"] = (string)$xml->{$settings['PL2']};
            }

            if(strpos($settings['PL3'], '[') !== false){
                $splitStr = explode("[", $settings['PL3']);
                $point = substr($splitStr[1], 0, -1);
                $array["PL3"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["PL3"] = (string)$xml->{$settings['PL3']};
            }

            if(strpos($settings['FA1'], '[') !== false){
                $splitStr = explode("[", $settings['FA1']);
                $point = substr($splitStr[1], 0, -1);
                $array["FA1"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["FA1"] = (string)$xml->{$settings['FA1']};
            }

            if(strpos($settings['FA2'], '[') !== false){
                $splitStr = explode("[", $settings['FA2']);
                $point = substr($splitStr[1], 0, -1);
                $array["FA2"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["FA2"] = (string)$xml->{$settings['FA2']};
            }

            if(strpos($settings['FA3'], '[') !== false){
                $splitStr = explode("[", $settings['FA3']);
                $point = substr($splitStr[1], 0, -1);
                $array["FA3"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["FA3"] = (string)$xml->{$settings['FA3']};
            }

            if(strpos($settings['ILT'], '[') !== false){
                $splitStr = explode("[", $settings['ILT']);
                $point = substr($splitStr[1], 0, -1);
                $array["ILT"] = (string)$xml->{$splitStr[0]}->$point;
            }else{
                $array["ILT"] = (string)$xml->{$settings['ILT']};
            }
        }else{
            $array = array("PL1"=>0,"PL2"=>0,"PL3"=>0,"FA1"=>0,"FA2"=>0,"FA3"=>0);
        }
        return $array;
    }

    function testMyXML($settings){
        $xml = simplexml_load_file("./pokus.xml");
        if($xml){
            echo (string)$xml->{$settings['PL1']};
            $array["PL1"] = (string)$xml->{"I1"}->P;
            $array["PL2"] = (string)$xml->{$settings['PL2']};
            $array["PL3"] = (string)$xml->{$settings['PL3']};
            $array["FA1"] = (string)$xml->{$settings['FA1']};
            $array["FA2"] = (string)$xml->{$settings['FA2']};
            $array["FA3"] = (string)$xml->{$settings['FA3']};
            $array["ILT"] = (string)$xml->{$settings['ILT']};
        }
        echo "PL1: ".$array["PL1"];
    }

    if(isset($_POST['portvr'])){
        $wattrouterSettings = array();

        $sql_wattrouter = "SELECT * FROM wattrouter_types WHERE id=".$_POST['typ'];
        $result_router = $conn->query($sql_wattrouter);
  
        if ($result_router->num_rows > 0) {
            // output data of each row
          while($row_router = $result_router->fetch_assoc()){
            $wattrouterSettings["PL1"] = $row_router['PL1'];
            $wattrouterSettings["PL2"] = $row_router['PL2'];
            $wattrouterSettings["PL3"] = $row_router['PL3'];
            $wattrouterSettings["FA1"] = $row_router['FA1'];
            $wattrouterSettings["FA2"] = $row_router['FA2'];
            $wattrouterSettings["FA3"] = $row_router['FA3'];
            $wattrouterSettings["ILT"] = $row_router['ILT'];
          }
        }

        //$wattrouterSettings = ['PL1' => "I1['P']", 'PL2' => "I2['P']", 'PL3' => "I3['P']", 'FA1' => "I4['P']", 'FA2' => "I5['P']", 'FA3' => "I6['P']", 'ILT' => "ILT"];
        $array = wattrouter($_POST['ip'], $_POST['port'], $wattrouterSettings);
        print_r($wattrouterSettings);
        echo "<br>";
        print_r($array);
        echo "<br>";
        testMyXML($wattrouterSettings);

    }

    if(isset($_POST['portvr_mail'])){
        System_mail($_POST['email'], "Test", "Test", 8, "Testovací mail", array());   
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Tester</title>
        <link rel="stylesheet" href="./lib/YearPicker/yearpicker.css">
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="../lib/jquery-ui/jquery-ui.js"></script>
    </head>
    <body>
        <form method="post">
            <input type="text" name="ip" placeholder="ip...">
            <input type="number" name="port" placeholder="port..." value="8083"><br>
            <select name="typ">
                <option value=1>Wattrouter M</option>
                <option value=2>Wattrouter MX</option>
            </select>
            <input type="submit" name="portvr">
        </form>
        <div>
            <?php
                echo "PL1 = ".$array['PL1']."</br>";
                echo "PL2 = ".$array['PL2']."</br>";
                echo "PL3 = ".$array['PL3']."</br>";
                echo "FA1 = ".$array['FA1']."</br>";
                echo "FA2 = ".$array['FA2']."</br>";
                echo "FA3 = ".$array['FA3']."</br>";
                echo "ILT = ".$array['ILT']."</br>";
            ?>
        </div>
        
        <br>
        <form id="test_mail" method="post">
            Test mail:
            <input type="mail" name="email"><br>
            <input type="submit" name="portvr_mail">  
        </form>
    </body>
</html>