<?
session_start();

if(isset($_GET["mesic2"])) $_SESSION["mesic2"] = $_GET["mesic2"]*1;
if(isset($_GET["rok"])) $_SESSION["rok2"] = $_GET["rok"]*1;

if(!isset($_SESSION["mesic2"])) $_SESSION["mesic2"] = date("n");
if(!isset($_SESSION["rok2"])) $_SESSION["rok2"] = date("Y");

$mesice = array(1=>"leden","únor","březen","duben","květen","červen","červenec","srpen","září","říjen","listopad","prosinec");

$od = mktime(0,0,0,$_SESSION["mesic2"],1,$_SESSION["rok2"]);
$do = mktime(0,0,0,$_SESSION["mesic2"]+1,1,$_SESSION["rok2"]);

$max=0;
$con = mysqli_connect("localhost","root","aeko.2353","spotreba");

$query = mysqli_query($con,"SELECT SUM(SP1), SUM(SP2), SUM(SP3), SUM(FA1), SUM(FA2), SUM(FA3) FROM data WHERE datum >= ".$od." AND datum < ".$do." GROUP BY(DAY(FROM_UNIXTIME(datum)))") or die(mysql_error());
while($radek=mysqli_fetch_assoc($query))
  $max = max($max,$radek["SUM(SP1)"],$radek["SUM(SP2)"],$radek["SUM(SP3)"],$radek["SUM(FA1)"],$radek["SUM(FA2)"],$radek["SUM(FA3)"]);

$max /= 60;
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="cs">
<title>WATTROUTER MĚSÍC</title>
</head>
<body>

  <a href="index.php" style="text-decoration:none;">Aktuální vývoj</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index2.php" style="text-decoration:none;">Den</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index3.php" style="text-decoration:none;">Měsíc</a><br>
  <br>

  <form method="get" action="index3.php">
  <div style="background-color:#aaaaaa;padding:5px;width:330px;">
    Zobrazit data za:
   <select name="mesic2"><? foreach($mesice as $id=>$name) echo "<option value=\"".$id."\"".($id==$_SESSION["mesic2"]?" selected=\"selected\"":"").">".$name;?></select>
    <select name="rok"><? for($i=2014;$i<=date("Y");$i++) echo "<option value=\"".$i."\"".($i==$_SESSION["rok2"]?" selected=\"selected\"":"").">".$i;?></select>&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Zobraz">
  </div>
  </form>

  <br>
<table cellpadding="0" cellspacing="0" style="width:100%;font-family:fantasy">
 <tr>
  <th>Fáze 1</th><th>Fáze 2</th>
 </tr>
 <tr>
   <th><img src="grafm.php?faze=1&amp;max=<?=$max?>&amp;od=<?=$_SESSION["mesic2"]."-".$_SESSION["rok2"]?>"></th>
   <th><img src="grafm.php?faze=2&amp;max=<?=$max?>&amp;od=<?=$_SESSION["mesic2"]."-".$_SESSION["rok2"]?>"></th>
 </tr>
 <tr>
  <th><br><br>Fáze 3</th><th><br><br>Souhrn fází</th>
 </tr>
 <tr>
   <th><img src="grafm.php?faze=3&amp;max=<?=$max?>&amp;od=<?=$_SESSION["mesic2"]."-".$_SESSION["rok2"]?>"></th>
   <th><img src="grafm.php?faze=-1&amp;od=<?=$_SESSION["mesic2"]."-".$_SESSION["rok2"]?>"></th>
 </tr>
</table><div style="width:90%;text-align:right;margin-top:40px;">© aeko <?=date("Y")?></div>

</body>
</html>
