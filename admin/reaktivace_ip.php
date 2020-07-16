<?php
    $title = "Reaktivace Ip adres";

    require_once "./header.inc.php";
    $opravneni = GetVariable($_SESSION['id'], "uzivatele", "opravneni");
  ?>
    <h3>Reaktivace ip adres</h3>
    <div class="templatemo-content-widget no-padding">
      <div class="panel panel-default table-responsive">
        <table id="ip-adresy" class="table table-striped table-bordered templatemo-user-table">
          <thead>
            <tr>
              <td style="cursor:pointer;" onclick="sortTable(0)">Název <span class="caret"></td>
              <td style="cursor:pointer;" onclick="sortTable(1)">Ip <span class="caret"></td>
              <td style="cursor:pointer;" onclick="sortTable(2)">Port <span class="caret"></td>
              <td style="cursor:pointer;" onclick="sortTable(3)">Druh zařízení <span class="caret"></td>
              <td style="cursor:pointer;" onclick="sortTable(4)">Typ zařízení <span class="caret"></td> 
              <td style="cursor:pointer;" onclick="sortTable(5)">Výkon <span class="caret"></td>
              <td><i class="fa fa-gear"></i> Možnosti</td>
            </tr>
          </thead>
          <tbody>
          <?php
            $limit = 10; //Počet zobrazenných uživatelů na jedné web stránce
            // Hledej GET, pokud nenajdeš tak je pn rovno 1
            if (isset($_GET["page"])) {  
              $pn  = $_GET["page"];  
            }  
            else {  
              $pn=1;  
            }; 
            
            $start_from = ($pn-1) * $limit;
  
            $sql = "SELECT * FROM ip_adresy 
                  INNER JOIN typy ON typy.id = ip_adresy.typ_id 
                  INNER JOIN jednotky ON jednotky.mocnina = ip_adresy.Jednotka";
            if ($result = $conn->query($sql)) {
              while ($row = $result->fetch_array()) {
                if($row['Deactivated'] != 0){
                  echo "<tr>";
                  
                  echo "<td>" . vypis($row['Ip_Nazev']) . "</td>";
                  echo "<td>" . vypis($row['ip']) . "</td>";
                  echo "<td>" . $row['port'] . "</td>";
                  echo "<td>" . vypis($row['druh']) . "</td>";
                  echo "<td>" . $row['Typ_název'] . "</td>";
                  echo "<td>" . $row['Vykon']." ".$row['Jednotka_nazev'] . "</td>";
                  ?>
                  <td><!-- Ikona na úpravu -->                                                
                    <a href="deactivate_ip.php?id=<?php echo $row[0]; ?>&Secret_key=<?php echo $row['Secret_key_ip']; ?>&deactivate=0"><button type="button" class="templatemo-blue-button" name="reactivate">Reaktivovat</button></a>
                  </td>
                  <?php
                  echo "</tr>";
                }                           
              }
  
              $result->close();
            }else{
              echo mysqli_error($conn);
            }
          ?>
          </tbody>  
        </table>
      </div>
      
      <div class="form-group text-right">
        <a href="zarizeni.php"><button type="button" class="templatemo-blue-button" name="add_ip">Zpet</button></a>
      </div> 
    </div>
  
  <script>
  function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("ip-adresy");
    switching = true;
    // Set the sorting direction to ascending:
    dir = "asc";
    /* Make a loop that will continue until
    no switching has been done: */
    while (switching) {
      // Start by saying: no switching is done:
      switching = false;
      rows = table.rows;
      /* Loop through all table rows (except the
      first, which contains table headers): */
      for (i = 1; i < (rows.length - 1); i++) {
        // Start by saying there should be no switching:
        shouldSwitch = false;
        /* Get the two elements you want to compare,
        one from current row and one from the next: */
        x = rows[i].getElementsByTagName("TD")[n];
        y = rows[i + 1].getElementsByTagName("TD")[n];
        /* Check if the two rows should switch place,
        based on the direction, asc or desc: */
        if (dir == "asc") {
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            // If so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        } else if (dir == "desc") {
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            // If so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        }
      }
      if (shouldSwitch) {
        /* If a switch has been marked, make the switch
        and mark that a switch has been done: */
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        // Each time a switch is done, increase this count by 1:
        switchcount ++;
      } else {
        /* If no switching has been done AND the direction is "asc",
        set the direction to "desc" and run the while loop again. */
        if (switchcount == 0 && dir == "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }
  
  function FindInTable() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("ip-adresy");
    tr = table.getElementsByTagName("tr");
  
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[7];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  } 
  
  </script>
  <?php
    require_once "./footer.inc.php";
  ?>