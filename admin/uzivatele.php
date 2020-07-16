<?php 
  $title= "Seznam uživatelů";
  require_once "./header.inc.php";
  $opravneni = GetVariable($_SESSION['id'], "uzivatele", "opravneni");
?>
  <h3>Uživatelé</h3>
  <div class="templatemo-content-widget no-padding">
    <div class="panel panel-default table-responsive">
      <table id="uzivatele" class="table table-striped table-bordered templatemo-user-table">
        <thead>
          <tr>
            <td style="cursor:pointer;" onclick="sortTable(0)">Nick <span class="caret"></td> 
            <td style="cursor:pointer;" onclick="sortTable(1)">E-mail <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(2)">Telefon <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(3)">Jméno <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(4)">Příjmení <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(5)">Adresa <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(6)">Město <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(7)">Nazev <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(8)">Druh zařízení <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(9)">Ip <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(10)">Port <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(11)">Vykon <span class="caret"></td>
            <td style="cursor:pointer;" onclick="sortTable(12)">Opravneni <span class="caret"></td> 
            <td>Možnosti</td>
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

          $sql = "SELECT * FROM uzivatele 
                INNER JOIN Opravneni ON Opravneni.id = uzivatele.opravneni 
                INNER JOIN ip_adresy ON ip_adresy.id = uzivatele.ip_id 
                INNER JOIN jednotky ON jednotky.mocnina = ip_adresy.jednotka";
          if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_array()) {
              echo "<tr>";
              
              echo "<td>" . $row['nick'] . "</td>";
              echo "<td>" . $row['email'] . "</td>";  
              echo "<td>" . vypis($row['telefon']) . "</td>"; 
              echo "<td>" . vypis($row['Jmeno']) . "</td>";
              echo "<td>" . vypis($row['Prijmeni']) . "</td>";
              echo "<td>" . vypis($row['Adresa']) . "</td>";
              echo "<td>" . vypis($row['Mesto']) . "</td>";
              echo "<td>" . vypis($row['Ip_Nazev']) . "</td>";
              echo "<td>" . vypis($row['druh']) . " -> " . vypis(GetVariable($row['wattrouter_type'], "wattrouter_types", "Nazev")) . "</td>";
              echo "<td>" . vypis($row['ip']) . "</td>";
              echo "<td>" . $row['port'] . "</td>";
              echo "<td>" . $row['Vykon']." ".$row['Jednotka_nazev'] . "</td>";
              echo "<td>" . $row['Opravneni_Nazev'] . "</td>";
              if($opravneni >= $row['opravneni']){
              ?>
              <td><!-- Ikona na úpravu -->                                                
                <a class="edit" href="./upravit.php?id=<?php echo $row[0]."&Secret_key=".$row['Secret_key']; ?>&Typ=0" class="confirmation">
                  <img class="logout" src="../vzhled/obrazky/edit.png" width="15" height="15" title="Upravit" alt="Edit">
                </a>
                <?php if($_SESSION['id'] != $row[0]){ //Protiopatření aby se admin nemohl sám sebe vymazat PS:pokud se mu to provede musí vypnout a zapnout prohlížeč jelikož se bude držet SESSION a nepůjde se mu dostat na webové stránky ?>
                  <img style="cursor:pointer;" class="logout" onclick='deleteItem(<?php echo $row[0]; ?>, "<?php echo $row['Secret_key']; ?>", "<?php echo $row['Jmeno']; ?>", "<?php echo $row['Prijmeni']; ?>")' src="../vzhled/obrazky/cancel.png" width="15" height="15" title="Odstranit" alt="Remove_user">
                <?php } ?>
              </td>
              <?php
              }  
              echo "</tr>";                           
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
      <a href="./registrace.php"><button type="button" class="templatemo-blue-button" name="register">Registrovat</button></a>
    </div> 
  </div>

<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("uzivatele");
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

function deleteItem(id, secret_key, jmeno, prijmeni) {
    if (confirm("Opravdu chceš uživatele:" + jmeno + " " + prijmeni + " smazat ? PS: Pokud ho tady smažeš tak pořád ale nesmažeš jeho přidělenou ip!")) {
      window.location = './remove.php?id=' + id + '&Secret_key=' + secret_key;
    }
    return false;
}
</script>
<?php
  require_once "./footer.inc.php";
?>