<?php
  $title = "Hlavní stránka";
  require_once "./header.inc.php";

?>
    <div class="templatemo-flex-row flex-content-row">
      <div class="templatemo-content-widget white-bg col-2">
        <i class="fa fa-times"></i>
        <div class="square"></div><h2>Vítej v adminské sekci <?php echo GetVariable($_SESSION['id'], "uzivatele", "Jmeno"); ?>.</h2>
      </div>
    </div>           
    <div class="templatemo-flex-row flex-content-row templatemo-overflow-hidden"> <!-- overflow hidden for iPad mini landscape view-->
      <div class="col-1 templatemo-overflow-hidden">
        <div class="templatemo-content-widget white-bg templatemo-overflow-hidden">
          <i class="fa fa-times"></i>
          <div class="templatemo-flex-row flex-content-row">
            <div class="col-1 col-lg-6 col-md-12">
              <h2 class="text-center">Uživatelé info</h2>
              <div id="pie_chart_div" class="templatemo-chart"></div> <!-- Pie chart div -->
            </div>
            <div class="col-1 col-lg-6 col-md-12">
              <h2 class="text-center">Nový uživatelé</h2>
              <div id="area_chart_div" class="templatemo-chart"></div> <!-- Bar chart div -->
            </div>  
          </div>                
        </div>
      </div>
    </div>  
    <!-- JS -->
    <script src="js/jquery-1.11.2.min.js"></script>      <!-- jQuery -->
    <script src="js/jquery-migrate-1.2.1.min.js"></script> <!--  jQuery Migrate Plugin -->
    <script src="https://www.google.com/jsapi"></script> <!-- Google Chart -->
    <script>
      /* Google Chart 
      -------------------------------------------------------------------*/
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart); 
      google.setOnLoadCallback(drawAreaChart); 
      
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

          // Create the data table.
          var data = new google.visualization.DataTable();
          data.addColumn('string', 'Topping');
          data.addColumn('number', 'Slices');
          data.addRows([
            <?php
              $sql = "SELECT op.Opravneni_Nazev AS Nazev, COUNT(*) AS Pocet FROM uzivatele AS uz JOIN Opravneni AS op ON uz.opravneni = op.id GROUP BY uz.opravneni";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // output data of each row
                $prikaz = "";
                while($row = $result->fetch_assoc()) {
                  $prikaz .= "['" . $row["Nazev"]. "', " . (string)$row["Pocet"]. "],";
                }
                substr($prikaz, 0, -1);  
                echo $prikaz;
              }
            ?>
          ]);

          // Set chart options
          var options = {'title':'Rozdělení uživatelů podle oprávnění'};

          // Instantiate and draw our chart, passing in some options.
          var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
          pieChart.draw(data, options);
      }

      function drawAreaChart() {
        areaData = google.visualization.arrayToDataTable([
            <?php
              $sql = "SELECT DATE(Vytvoren) AS Datum,
                      COUNT(*) AS Pocet
                      FROM   uzivatele
                      GROUP BY DATE(Vytvoren)
                      ORDER BY Datum";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                // output data of each row
                echo "['Den', 'Počet']";
                while($row = $result->fetch_assoc()) {
                  echo ", ['" . $row["Datum"]. "', " . $row["Pocet"]. "]";
                }
              }
            ?>
        ]);

        areaOptions = {
          title: 'Company Performance',
          hAxis: {title: 'Den',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        areaChart = new google.visualization.AreaChart(document.getElementById('area_chart_div'));
        areaChart.draw(areaData, areaOptions);
      }

      $(document).ready(function(){
        if($.browser.mozilla) {
          //refresh page on browser resize
          // http://www.sitepoint.com/jquery-refresh-page-browser-resize/
          $(window).bind('resize', function(e)
          {
            if (window.RT) clearTimeout(window.RT);
            window.RT = setTimeout(function()
            {
              this.location.reload(false); /* false to get page from cache */
            }, 200);
          });      
        } else {
          $(window).resize(function(){
            drawChart();
          });  
        }   
      });
      
    </script>
    <script type="text/javascript" src="js/templatemo-script.js"></script>      <!-- Templatemo Script -->

<?php
  require_once "./footer.inc.php";
?>