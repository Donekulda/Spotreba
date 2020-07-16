<?
session_start();

if(isset($_GET["den"])) $_SESSION["den"] = $_GET["den"]*1;
if(isset($_GET["mesic"])) $_SESSION["mesic"] = $_GET["mesic"]*1;
if(isset($_GET["rok"])) $_SESSION["rok"] = $_GET["rok"]*1;

if(!isset($_SESSION["den"])) $_SESSION["den"] = date("j"); 
if(!isset($_SESSION["mesic"])) $_SESSION["mesic"] = date("n");
if(!isset($_SESSION["rok"])) $_SESSION["rok"] = date("Y");

$mesice = array(1=>"ledna","února","března","dubna","května","června","července","srpna","září","října","listopadu","prosince");



$con = mysqli_connect("localhost","root","aeko.2353","spotreba");
  
$timestamp = mktime(0,0,0,$_SESSION["mesic"],$_SESSION["den"],$_SESSION["rok"])+86400;
$od = $timestamp-86400;

$query = mysqli_query($con,"SELECT MAX(SP1) SP1, MAX(SP2) SP2, MAX(SP3) SP3, MAX(FA1) FA1, MAX(FA2) FA2, MAX(FA3) FA3 from data WHERE datum BETWEEN ".$od." AND ".$timestamp);
$data = mysqli_fetch_assoc($query);
$max = max($data["SP1"],$data["SP2"],$data["SP3"],$data["FA1"],$data["FA2"],$data["FA3"])*1000;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="cs">
<title>WATTROUTER DEN</title>
</head>
<body>

  <a href="index.php" style="text-decoration:none;">Aktuální vývoj</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index2.php" style="text-decoration:none;">Den</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index3.php" style="text-decoration:none;">Měsíc</a><br>
  <br>

  <form method="get" action="index2.php">
  <div style="background-color:#aaaaaa;padding:5px;width:420px;">
    Zobrazit data ze dne: 
    <select name="den"><? for($i=1;$i<=31;$i++) echo "<option value=\"".$i."\"".($i==$_SESSION["den"]?" selected=\"selected\"":"").">".$i;?></select>.
    <select name="mesic"><? foreach($mesice as $id=>$name) echo "<option value=\"".$id."\"".($id==$_SESSION["mesic"]?" selected=\"selected\"":"").">".$name;?></select>
    <select name="rok"><? for($i=2014;$i<=date("Y");$i++) echo "<option value=\"".$i."\"".($i==$_SESSION["rok"]?" selected=\"selected\"":"").">".$i;?></select>&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Zobraz">
  </div>
  </form>
  
  <br>
<table cellpadding="0" cellspacing="0" style="width:100%;font-family:fantasy">
 <tr>
  <th>Fáze 1</th><th>Fáze 2</th>
 </tr>
 <tr>
   <th><img src="graf.php?faze=1&amp;max=<?=$max?>&amp;od=<?=$od?>" width="85%"></th> 
   <th><img src="graf.php?faze=2&amp;max=<?=$max?>&amp;od=<?=$od?>" width="85%"></th> 
 </tr>
 <tr>
  <th><br><br>Fáze 3</th><th><br><br>Souhrn fází</th>
 </tr>
 <tr>
   <th><img src="graf.php?faze=3&amp;max=<?=$max?>&amp;od=<?=$od?>" width="85%"></th> 
   <th><img src="graf.php?faze=-1&amp;od=<?=$od?>" width="85%"></th> 
 </tr>
</table>
<div style="width:90%;text-align:right;margin-top:40px;">© aeko <?=date("Y")?></div>
</body>
</html>
