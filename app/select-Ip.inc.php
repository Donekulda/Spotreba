        <?php if(GetVariable($_SESSION['id'], "uzivatele", "opravneni") > 1 && isset($_SESSION['id'])){ ?>
           <form id="formZarizeni" class="search-bar" action="" method="post">
                <div class="form-control-wrapper">
                  <select name="ip_id" class="form-control select2" data-placeholder="Zvol ip">
                    <?php
                    $sql = "SELECT * from ip_adresy"; 
                    $query = $_SESSION['connection']->query($sql);
                
                    if ($query->num_rows > 0) {
                        // output data of each row
                        while($row = $query->fetch_assoc()) {
                          if($row["Deactivated"] == 0)
                            echo "<option onclick='document.getElementById(\"formZarizeni\").submit();' value=\"".$row['id']."\"".($row['id']==$_SESSION["show_ip"]?" selected=\"selected\"":"").">".$row['Ip_Nazev']."</option>";
                        }
                    }
                    ?>
                  </select>
                </div><!-- form-control-wrapper -->
                <button id="searchBtn" class="btn btn-orange"><i class="fa fa-paper-plane"></i></button>
            </form>
            <!-- ip selector -->
        <?php } ?>