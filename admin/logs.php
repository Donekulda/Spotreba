<?php
  error_reporting(-1);
  ini_set('display_errors', 'On');
    $title="Seznam logů";
  require_once "./header.inc.php";
  $opravneni = GetVariable($_SESSION['id'], "uzivatele", "opravneni");
?>
<h3>Logy</h3>
  <div class="templatemo-content-widget no-padding">
    <div class="panel panel-default table-responsive">
      <!--<input type="text" id="myInput" onkeyup="FindInTable()" placeholder="Hledat podle názvu.."> -->
      <table id="log" class="table table-striped table-bordered templatemo-user-table">
        <thead>
          <tr>
            <th onclick="sortTable(0)">Kdy</th> 
            <th onclick="sortTable(1)">Uzivatel</th>
            <th onclick="sortTable(2)">Hodnost</th>
            <th onclick="sortTable(3)">Log</th>
            <th>Upravit</th>
          </tr>
        </thead>
        <?php
        $sql = "SELECT * FROM logs 
              INNER JOIN predlohy ON predlohy.id = logs.druh_logu
              INNER JOIN Opravneni ON Opravneni.id = logs.opravneni";
        if ($result = $conn->query($sql)) {

          while ($row = $result->fetch_array()) {
            $text = $row['Log_Nazev']."<br>".$row['Obsah'];
            echo "<tr>";
                                                  
            echo "<td>" . $row['cas'] . "</td>";
            echo "<td>" . GetVariable($row['uzivatelId'], "uzivatele", "nick") . "</td>";
            echo "<td>" . $row['Opravneni_Nazev'] . "</td>";
            echo "<td> <div class='log'>" . $text. "</div></td>"; 
            echo "</tr>";                           
          }

          $result->close();
        }else{
          echo $conn->error;
        }
      ?>  
      </table>
    </div>
  </div>
<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("log");
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
  table = document.getElementById("uzivatele");
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