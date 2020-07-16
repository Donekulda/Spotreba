<?
$con = mysqli_connect("localhost","root","aeko.2353","spotreba");

  
$timestamp = mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"))-60;
$od = $timestamp-6*3600;

$query = mysqli_query($con,"SELECT MAX(SP1) SP1, MAX(SP2) SP2, MAX(SP3) SP3, MAX(FA1) FA1, MAX(FA2) FA2, MAX(FA3) FA3 from data WHERE datum BETWEEN ".$od." AND ".$timestamp);
$data = mysqli_fetch_assoc($query);
$max = max($data["SP1"],$data["SP2"],$data["SP3"],$data["FA1"],$data["FA2"],$data["FA3"])*1000;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Language" content="cs">
  <meta http-equiv="refresh" content="60">
<title>WATTROUTER</title>
</head>
<body>
<a href="index.php" style="text-decoration:none;">Aktuální vývoj</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index2.php" style="text-decoration:none;">Den</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index3.php" style="text-decoration:none;">Měsíc</a><br>
<table cellpadding="0" cellspacing="0" style="width:100%;font-family:fantasy">
 <tr>
  <th>Fáze 1</th><th>Fáze 2</th>
 </tr>
 <tr>
   <th><img src="graf.php?faze=1&amp;max=<?=$max?>" width="85%"></th> 
   <th><img src="graf.php?faze=2&amp;max=<?=$max?>" width="85%"></th> 
 </tr>
 <tr>
  <th><br><br>Fáze 3</th><th><br><br>Souhrn fází</th>
 </tr>
 <tr>
   <th><img src="graf.php?faze=3&amp;max=<?=$max?>" width="85%"></th> 
   <th><img src="graf.php?faze=-1" width="85%"></th> 
 </tr>
</table>
<div style="width:90%;text-align:right;margin-top:40px;">© aeko <?=date("Y")?></div>
</body>
</html>
