<?php
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
    $_SESSION['show_ip'] = $_SESSION['ip'];
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
      /*
      function addData(GrafObj) {
        var DataPointsSP = [];
        var DataPointsFA = [];

        $.ajax({
            type: "GET",               
            url: "../ajax/SberDatGrafu.php",
            dataType: "json",
            data: {od: (Math.floor(Date.now() / 1000) - 35), max: GrafObj._max, faze: GrafObj._faze},
            success: function(data){
                GrafObj._odKdy = data['odKdy'];
                GrafObj._doKdy = data['doKdy'];

                DataPointsSP = data.dataSP;
                DataPointsFA = data.dataFA;

                for (var i = GrafObj._dataPoints0.length; i < GrafObj._dataPoints0.length + DataPointsSP.length; i++) {
                    GrafObj._dataPoints0.push({
                        x: DataPointsSP[i][0],
                        y: DataPointsSP[i][1],
                        color: DataPointsSP[i][2]
                    });
                }

                for (var i = GrafObj._dataPoints1.length; i < GrafObj._dataPoints1.length + DataPointsFA.length; i++) {
                    GrafObj._dataPoints1.push({
                        x: DataPointsFA[i][0],
                        y: DataPointsFA[i][1],
                        color: DataPointsFA[i][2]
                    });
                }     

                GrafObj._chart.render();
        
                console.log("update");
                setTimeout(function() { addData(GrafObj) }, 60000);
            }
        });
      }
      */

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

          for (var i = 0; i < chart.options.data[1].dataPoints.length - 1; i++) {
              multiColoredAreaChart.push({
                  type: "area",
                  fillOpacity: chart.options.data[1].fillOpacity || 0.95,
                  toolTipContent: null,
                  highlightEnabled: true,
                  showInLegend: false,
                  markerSize: 1,
                  color: chart.options.data[1].dataPoints[i].color,
                  dataPoints: [{
                      x: chart.options.data[1].dataPoints[i].x,
                      y: chart.options.data[1].dataPoints[i].y
                  }, {
                      x: chart.options.data[1].dataPoints[i + 1].x,
                      y: chart.options.data[1].dataPoints[i + 1].y
                  }]
              });
          }

          var options = chart.options.data[0];
          options.type = "line";
          options.name = options.name || "DataSeries 1";
          options.color = "black";
          options.xValueFormatString = "H:mm";
          options.legendMarkerType = "square";
          options.culture = "cz";

          multiColoredAreaChart.push(options);


          var options = chart.options.data[1];
          options.type = "line";
          options.name = options.name || "DataSeries 2";
          options.color = "green";
          options.xValueFormatString = "H:mm";
          options.legendMarkerType = "square";
          options.culture = "cz";

          multiColoredAreaChart.push(options);

          chart.options.data = multiColoredAreaChart;

          chart.options.toolTip = {
              shared: true,
              content: "<b style='\"'color: {color};'\"'>{name}: </b> {y} Wh",
              borderColor: "black"
          };

      }

      function CreateNewGraf(GrafObj){
        var DataPointsSP = [];
        var DataPointsFA = [];

        $.ajax({
            type: "GET",               
            url: "../ajax/SberDatGrafu.php",
            dataType: "json",
            data: {od: GrafObj._od, max: GrafObj._max, faze: GrafObj._faze},
            success: function(data){
                GrafObj._odKdy = data['odKdy'];
                GrafObj._doKdy = data['doKdy'];

                GrafObj._souhrnFA = data['souhrnFA'];
                GrafObj._souhrnSP = data['souhrnSP'];
                GrafObj._vykon = data['vykon'];

                DataPointsSP = data.dataSP;
                DataPointsFA = data.dataFA;

                GrafObj._dataPoints0 = [];
                GrafObj._dataPoints1 = [];

                for (var i = 0; i < DataPointsSP.length; i++) {
                    GrafObj._dataPoints0.push({
                        x: DataPointsSP[i][0],
                        y: DataPointsSP[i][1],
                        color: DataPointsSP[i][2]
                    });
                }

                for (var i = 0; i < DataPointsFA.length; i++) {
                    GrafObj._dataPoints1.push({
                        x: DataPointsFA[i][0],
                        y: DataPointsFA[i][1],
                        color: DataPointsFA[i][2]
                    });
                }     

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
                    title: "Wh",
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
                          showInLegend: true,
                          legendMarkerType: "square",
                          type: "area",
                          markerSize: 0,
                          fillOpacity: .9,
                          xValueType: "dateTime",
                          dataPoints: GrafObj._dataPoints0
                        },
                        {
                          name: "Výroba",
                          showInLegend: true,
                          legendMarkerType: "square",
                          type: "area",
                          color: "rgba(64, 128, 0, 0.8)",
                          markerSize: 0,
                          fillOpacity: .9,
                          xValueType: "dateTime",
                          dataPoints: GrafObj._dataPoints1
                        }
                  ]
                });

                renderMultiColoredAreaChart(GrafObj._chart);
                GrafObj._chart.render();

                var spotreba = document.createElement("p");
                spotreba.innerHTML = "Dnešní spotřeba: " + GrafObj._souhrnSP + " Wh";
                document.getElementById(GrafObj._id_div).appendChild(spotreba);
                spotreba.style.position = "absolute";
                spotreba.style.color = "red";
                spotreba.style.top = 89 + "%";
                spotreba.style.left = 63 + "%";
        
                var vyroba = document.createElement("p");
                vyroba.innerHTML = "Dnešní výroba: " + GrafObj._souhrnFA + " Wh";
                document.getElementById(GrafObj._id_div).appendChild(vyroba);
                vyroba.style.position = "absolute";
                vyroba.style.color = "green";
                vyroba.style.top = 89 + "%";
                vyroba.style.left = 15 + "%";

                var vykon = document.createElement("p");
                vykon.innerHTML = "Výkon elektrárny: " + GrafObj._vykon;
                document.getElementById(GrafObj._id_div).appendChild(vykon);
                vykon.style.position = "absolute";
                vykon.style.color = "black";
                vykon.style.top = 94 + "%";
                vykon.style.left = 63 + "%";

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