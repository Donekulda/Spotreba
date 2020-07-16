<?php
  /// MySQL Settings
  $db_host = "host_mysql";
  $db_user = "user_mysql";
  $db_pass = "pass_mysql";
  $db_db = "db_name";
  
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_db);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  mysqli_set_charset($conn,"utf8");
  
  $_SESSION['connection'] = $conn; 
  
  /// Mail server settings
  $mail_settings = array();
  $mail_settings["Host"] = "host.server_mail"; // SMTP server address 
  $mail_settings["Port"] = "465"; // SMTP port
  $mail_settings["User"] = "nick_mail"; // SMTP authentication username 
  $mail_settings["Pass"] = "pass_mail"; // SMTP authentication password
  $mail_settings["From"] = "noreply@mail.cz"; // Output sending email address
  $mail_settings["enc"] = "ssl"; // SMTP encryption system
  
  /// Other
  $adresa = "http://spotreba.solarnivyroba.cz/"; // Default domain address on which the website is hosted
   
  $default_port = 8080; // Wattrouter default port

  $system_mail = "system@mail.cz"; // Email for system error messages, user password
  $Org_name = "spotreba s.r.o."; // Name of your org. 

  $Org_name_head = "Aeko"; // Used in header of menu for home url
  
?>