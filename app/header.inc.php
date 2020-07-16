<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
  if($title != "Přihlášení"){
    if(GetVariable($_SESSION['id'], "uzivatele", "opravneni")<1){
      header("location: ./login.php");
    }
  }
*/      
  if(isset($_SESSION['id'])){
    $_SESSION['ip'] = GetVariable($_SESSION['id'], "uzivatele", "ip_id");
    $_SESSION['jmeno'] = GetVariable($_SESSION['id'], "uzivatele", "Jmeno");
    $_SESSION['prijmeni'] = GetVariable($_SESSION['id'], "uzivatele", "Prijmeni");
    $_SESSION['opravneni'] = GetVariable($_SESSION['id'], "uzivatele", "opravneni");
  }else{
    $_SESSION['ip'] = 0;
  }   

  if(isset($_POST['ip_id'])){
    $_SESSION['show_ip'] = $_POST['ip_id'];
  }else{
    if(!isset($_SESSION['show_ip']) || $_SESSION['show_ip'] == 0)
      $_SESSION['show_ip'] = $_SESSION['ip'];
  } 

  if(isset($_SESSION['show_ip'])){
    $delic = GetVariable(GetVariable($_SESSION['show_ip'], "ip_adresy", "faze_id"), "Faze", "delic");
    $pocetVstupu = GetVariable(GetVariable($_SESSION['show_ip'], "ip_adresy", "faze_id"), "Faze", "pocet");
  }else{
    $delic = 1;
  }

  $mesice = [
    1 => 'Leden', 'Únor', 'Březen', 'Duben', 'Květen',
    'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen',
    'Listopad', 'Prosinec'
  ]; 
?>
<!DOCTYPE html>
<html lang="cz">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Facebook -->
    <meta property="og:url" content="http://spotreba.solarnivyroba.cz">
    <meta property="og:title" content="Aeko - spotřeba">
    <meta property="og:description" content="Grafy zobrazující stav a výrobu připojených elektráren a výsledků z elektroměrů.">

    <!-- Preview image -->
    <meta property="og:image" content="../img/preview_image.png">
    <meta property="og:image:secure_url" content="../img/preview_image.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Webová aplikace společnosti Aeko s.r.o. pro náhled na spotřebu a výrobu fotovoltaických elektráren a objektů na nich připojených.">
    <meta name="author" content="Mikuláš Staněk">

    <!-- vendor css -->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="../lib/jquery-toggles/toggles-full.css" rel="stylesheet">
    <link href="../lib/select2/css/select2.min.css" rel="stylesheet">   
    <link href="../lib/morris.js/morris.css" rel="stylesheet">


    <!-- Amanda CSS -->
    <link rel="stylesheet" href="../css/amanda.min.css">      
    
    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/jquery-ui/jquery-ui.js"></script>

  <?php if(!isset($subtitle)){ ?>
    <title><?php echo $title; ?></title>
  <?php }else{ ?>
    <title><?php echo $title." - ".$subtitle; ?></title>
  <?php } 
    if($subtitle == "Aktuální vývoj" or $subtitle == "Denní vývoj"){
  ?>
  
    <script src="../lib/canvasJs/canvasjs.min.js"></script>
    <script>
      CanvasJS.addCultureInfo("cz", {
        decimalSeparator: ",",
        digitGroupSeparator: " ",
        days: ["Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota", "Neděle"],
        shortDays: ["Pon", "Út", "St", "Čt", "Pá", "So", "Ne"],
        months: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
        shortMonths: ['Led', 'Ún', 'Břez', 'Dub', 'Květ', 'Červ', 'Čec', 'Srp', 'Zář', 'Říj', 'List', 'Pros']
      });

      function renderMultiColoredAreaChart(chart) {
          var multiColoredAreaChart = [];
          for (var i = 0; i < chart.options.data[0].dataPoints.length - 1; i++) {
              multiColoredAreaChart.push({
                  type: "area",
                  fillOpacity: chart.options.data[0].fillOpacity || 1,
                  toolTipContent: null,
                  highlightEnabled: false,
                  showInLegend: false,
                  markerSize: 0,
                  color: chart.options.data[0].dataPoints[i].color,
                  dataPoints: [{
                      x: chart.options.data[0].dataPoints[i].x,
                      y: chart.options.data[0].dataPoints[i].y
                  }, {
                      x: chart.options.data[0].dataPoints[i + 1].x,
                      y: chart.options.data[0].dataPoints[i + 1].y
                  }]
              });
          }

        <?php for($i = 1; $i <= $pocetVstupu; $i++){ ?>
          for (var i = 0; i < chart.options.data[1].dataPoints.length - 1; i++) {
              multiColoredAreaChart.push({
                  type: "area",
                  fillOpacity: chart.options.data[1].fillOpacity || 0.95,
                  toolTipContent: null,
                  highlightEnabled: true,
                  showInLegend: false,
                  markerSize: 1,
                  color: chart.options.data[<?php echo $i; ?>].dataPoints[i].color,
                  dataPoints: [{
                      x: chart.options.data[<?php echo $i; ?>].dataPoints[i].x,
                      y: chart.options.data[<?php echo $i; ?>].dataPoints[i].y
                  }, {
                      x: chart.options.data[<?php echo $i; ?>].dataPoints[i + 1].x,
                      y: chart.options.data[<?php echo $i; ?>].dataPoints[i + 1].y
                  }]
              });
          }
        <?php } ?>

          var options = chart.options.data[0];
          options.type = "line";
          options.name = options.name || "DataSeries 1";
          options.color = "black";
          options.xValueFormatString = "H:mm";
          options.legendMarkerType = "square";
          options.culture = "cz";

          multiColoredAreaChart.push(options);
        
      <?php if($delic > 1){
          $poleZelenych = ["#00FF00","#00D100","#00AB00","#009000"]; ?>

          var options = chart.options.data[1];
          options.type = "line";
          options.name = options.name || "DataSeries 1";
          options.color = "#007000";
          options.xValueFormatString = "H:mm";
          options.legendMarkerType = "square";
          options.culture = "cz";

          multiColoredAreaChart.push(options);

        <?php for($i = ($pocetVstupu+1); $i > 1; $i--){ ?>
          var options = chart.options.data[<?php echo $i; ?>];
          options.type = "line";
          options.name = options.name || "DataSeries <?php echo $i; ?>";
          options.color = "black<?php //echo $poleZelenych[$i-2]; ?>";
          options.xValueFormatString = "H:mm";
          options.legendMarkerType = "square";
          options.culture = "cz";

          multiColoredAreaChart.push(options);
        <?php } ?>
      <?php }else{ ?>
          var options = chart.options.data[1];
          options.type = "line";
          options.name = options.name || "DataSeries 2";
          options.color = "#367E1F";
          options.xValueFormatString = "H:mm";
          options.legendMarkerType = "square";
          options.culture = "cz";

          multiColoredAreaChart.push(options);
      <?php } ?>

          chart.options.data = multiColoredAreaChart;

          chart.options.toolTip = {
              shared: true,
              content: "<b style='\"'color: {color};'\"'>{name}: </b> {y} W",
              borderColor: "black"
          };

      }

      function CreateNewGraf(GrafObj){
        var DataPointsSP = [];

      <?php for($i = 0; $i <= $pocetVstupu; $i++){ ?>
        var DataPointsFA<?php echo $i; ?> = [];
      <?php } ?>

        $.ajax({
            type: "GET",               
            url: "../ajax/SberDatGrafu.php",
            dataType: "json",
            data: {od: GrafObj._od, max: GrafObj._max, faze: GrafObj._faze, TypFaze: <?php echo $delic; ?>, pocet: <?php echo $pocetVstupu; ?> },
            success: function(data){
                GrafObj._odKdy = data['odKdy'];
                GrafObj._doKdy = data['doKdy'];

                GrafObj._souhrnFA = data['souhrnFA'];
                GrafObj._souhrnSP = data['souhrnSP'];
                GrafObj._souhrnSumSP = data['souhrnSumSP']
                GrafObj._pretok = data['pretokSum']
                GrafObj._vykon = data['vykon'];

              <?php if($delic > 1){ ?>
                GrafObj._arrayANDI = data['vykonANDI'];
              <?php } ?>

                DataPointsSP = data.dataSP;

              <?php
              if($delic>1){
               for($i = 0; $i <= $pocetVstupu; $i++){ ?>
                DataPointsFA<?php echo $i; ?> = data.dataFA<?php echo $i; ?>;
              <?php }}else{ ?>
                DataPointsFA0 = data.dataFA;
              <?php } ?>

                GrafObj._dataPoints0 = [];

                for (var i = 0; i < DataPointsSP.length; i++) {
                    GrafObj._dataPoints0.push({
                        x: DataPointsSP[i][0],
                        y: DataPointsSP[i][1],
                        color: DataPointsSP[i][2]
                    });
                }

              <?php if($delic>1){ ?>
              <?php for($i = 0; $i <= $pocetVstupu; $i++){ ?>
                GrafObj._dataPoints<?php echo $i+1; ?> = [];

                for (var i = 0; i < DataPointsFA<?php echo $i; ?>.length; i++) {
                    GrafObj._dataPoints<?php echo $i+1; ?>.push({
                        x: DataPointsFA<?php echo $i; ?>[i][0],
                        y: DataPointsFA<?php echo $i; ?>[i][1],
                        color: DataPointsFA<?php echo $i; ?>[i][2]
                    });
                }     
              <?php } }else{?>
                GrafObj._dataPoints1 = [];

                for (var i = 0; i < DataPointsFA0.length; i++) {
                    GrafObj._dataPoints1.push({
                        x: DataPointsFA0[i][0],
                        y: DataPointsFA0[i][1],
                        color: DataPointsFA0[i][2]
                    });
                }  
              <?php } ?>

                GrafObj._chart = new CanvasJS.Chart(GrafObj._id_div, {
                  animationEnabled: true,
                  zoomEnabled: true,
                  culture: "cz",
                  title: {
                    text: GrafObj._title
                  },
                  axisX: {
                    valueFormatString: "H:mm",
                    manimum: GrafObj._odKdy,
                    maximum: GrafObj._doKdy
                  },
                  axisY: {
                    title: "W",
                    maximum: GrafObj._max
                  },
                  legend: {
                    verticalAlign: "top",
                    horizontalAlign: "right",
                    dockInsidePlotArea: true
                  },
                  toolTip: {
                    shared: true
                  },
                  data: [{
                          name: "Spotřeba",
                          showInLegend: false,
                          legendMarkerType: "square",
                          type: "area",
                          markerSize: 0,
                          fillOpacity: .9,
                          xValueType: "dateTime",
                          dataPoints: GrafObj._dataPoints0
                        },
                      <?php if($delic > 1){ ?>
                        {
                          name: "Výkon celkem",
                          showInLegend: false,
                          legendMarkerType: "square",
                          type: "area",
                          color: "rgba(64, 128, 0, 0.8)",
                          markerSize: 0,
                          fillOpacity: 1,
                          xValueType: "dateTime",
                          dataPoints: GrafObj._dataPoints<?php echo $pocetVstupu+1; ?>
                        }

                      <?php $i_pokus_DataPoints = 3; 
                        for($i = $pocetVstupu; $i >= 1; $i--){?>
                          ,{
                            name: "Výkon <?php echo "FVE". $i; ?>",
                            showInLegend: false,
                            legendMarkerType: "square",
                            type: "area",
                            color: DataPointsFA<?php echo $i-1; ?>[0][2],
                            markerSize: 0,
                            fillOpacity: 1,
                            xValueType: "dateTime",
                            dataPoints: GrafObj._dataPoints<?php echo $i; ?>
                          }
                      <?php } }else{ ?>
                        {
                          name: "Výkon",
                          showInLegend: false,
                          legendMarkerType: "square",
                          type: "area",
                          color: "rgba(64, 128, 0, 0.8)",
                          markerSize: 0,
                          fillOpacity: .9,
                          xValueType: "dateTime",
                          dataPoints: GrafObj._dataPoints1
                        }
                      <?php } ?>
                  ]
                });

                renderMultiColoredAreaChart(GrafObj._chart);
                GrafObj._chart.render();

                var spotreba = document.createElement("p");
                spotreba.innerHTML = "Dodávka z/do DS: " + GrafObj._souhrnSP + " / <span style='color: #007000;'>" + GrafObj._pretok + "</span> kWh";
                document.getElementById(GrafObj._id_div).appendChild(spotreba);
                spotreba.style.position = "absolute";
                spotreba.style.color = "red";
                spotreba.style.top = 89 + "%";
                spotreba.style.left = 63 + "%";

                var spotreba = document.createElement("p");
                spotreba.innerHTML = "Celková spotřeba: " + GrafObj._souhrnSumSP + " kWh";
                document.getElementById(GrafObj._id_div).appendChild(spotreba);
                spotreba.style.position = "absolute";
                spotreba.style.color = "red";
                spotreba.style.top = 94 + "%";
                spotreba.style.left = 63 + "%";
        
                var vyroba = document.createElement("p");
                vyroba.innerHTML = "<?php echo $inform_text; ?> výroba: " + GrafObj._souhrnFA + " kWh";
                document.getElementById(GrafObj._id_div).appendChild(vyroba);
                vyroba.style.position = "absolute";
                vyroba.style.color = "green";
                vyroba.style.top = 89 + "%";
                vyroba.style.left = 15 + "%";

                <?php if($delic >1){
                  for($i = 0; $i < $pocetVstupu; $i++){ 
                ?>

                var vyroba = document.createElement("p");
                vyroba.innerHTML = "FVE<?php echo $i+1; ?>: " + GrafObj._arrayANDI[<?php echo $i; ?>] + " kWh";
                document.getElementById(GrafObj._id_div).appendChild(vyroba);
                vyroba.style.position = "absolute";
                vyroba.style.color = DataPointsFA<?php echo $i; ?>[0][2];
                vyroba.style.top = 94 + "%";
                vyroba.style.left = <?php echo (15 + ($i*20)); ?> + "%";

                <?php
                  }
                } ?>

/*
                var vykon = document.createElement("p");
                vykon.innerHTML = "Výkon elektrárny: " + GrafObj._vykon;
                document.getElementById(GrafObj._id_div).appendChild(vykon);
                vykon.style.position = "absolute";
                vykon.style.color = "black";
                vykon.style.top = 94 + "%";
                vykon.style.left = 63 + "%";
*/
                setTimeout(function() { CreateNewGraf(GrafObj) }, 60000);
            }
        });
      }
    </script>

    <?php } ?>
  </head>
  <body>
  <?php
    include_once "./topbar.inc.php";
    include_once "./menu.inc.php";
  ?>
  <div class="am-mainpanel">