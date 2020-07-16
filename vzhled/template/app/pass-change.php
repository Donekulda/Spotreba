<?php
    session_start();
  require_once "../config.inc.php";
  require_once "../functions.inc.php";
  
  if(!IsLogged($title, $login_url))
    header("location: ../error.php");
    
  if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") < 2){
    header("location: ../error.php");
  }

  if(isset($_POST['edit'])){
    if(isset($_POST['oldPass'])){
      if(password_verify($_POST['oldPass'], GetVariable($id, "uzivatele", "pass"))){
        if(isset($_POST['newPass'])){
          $password = password_hash($_POST['newPass'],PASSWORD_DEFAULT);
          ChangeString($id, "uzivatele", "pass", $password);
        }
      }
    }
  }
    
  $id = (int)$_GET['id'];
  $key = $_GET['Secret_key'];
  
  if(!($Data_key = GetVariable($id, "uzivatele", "Secret_key"))){ //Kontroluje zda dostal Secret_key z database
    echo $conn->error;
  }
        
  if($key != $Data_key){ // Porovnává klíče
    echo "<div class = 'error center'>No tak to je blblý kámo,<br />tvůj zadaný klíč je špatný!!!</div>";              
  }else{
    $ip_id = GetVariable($id, "uzivatele", "ip_id");
    $mocnina = GetVariable($id, "uzivatele", "jednotka");
?>
<form method = "post"> 
  <div class="input-group">
    <label>Staré heslo: </label>
    <input type="password" name="oldPass" class="box">
  </div> 
  <div class="input-group">
    <label>Nové Heslo: </label>
    <input type="password" name="newPass" id="newPass" class="box">
  </div>     
  <div class="input-group">
    <label>Nové heslo znovu: </label>
    <input type="password" name="newPassAgain" id="newPassAgain" onChange="checkPasswordMatch()" class="box">
    <p id="validate-status"></p>
  </div> 
  <div class="input-group">
    <button type="submit" class="btn" name="edit">Upravit</button>
  </div> 
</form>

<script>
function checkPasswordMatch() {
    var password = $("#newPass").val();
    var confirmPassword = $("#newPassAgain").val();

    if (password != confirmPassword)
        $("#divCheckPasswordMatch").html("Passwords do not match!");
    else
        $("#divCheckPasswordMatch").html("Passwords match.");
}

$(document).ready(function () {
   $("#newPass, #newPassAgain").keyup(checkPasswordMatch);
});
</script>
<?php
  }  
  require_once "../footer.php";
?>