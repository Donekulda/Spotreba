<?php
    function CreateGraf($id_div, $faze, $od, $max, $nazev){
?>
        <?php echo $id_div; ?>AreaGraf = new Object();

        <?php echo $id_div; ?>AreaGraf._id_div = "<?php echo $id_div; ?>";
        <?php echo $id_div; ?>AreaGraf._title = "<?php echo (-1 == $faze)?"Součet fází":("Fáze ".$faze).(($nazev != "")?(" - ".$nazev):""); ?>";
        <?php echo $id_div; ?>AreaGraf._faze = <?php echo $faze; ?>;
        <?php echo $id_div; ?>AreaGraf._od = <?php echo $od; ?>;
        <?php echo $id_div; ?>AreaGraf._max = <?php echo $max; ?>;
        <?php echo $id_div; ?>AreaGraf._souhrnFA = 0;
        <?php echo $id_div; ?>AreaGraf._souhrnSP = 0;
        <?php echo $id_div; ?>AreaGraf._pretok = 0;
        <?php echo $id_div; ?>AreaGraf._souhrnSumSP = 0;
        <?php echo $id_div; ?>AreaGraf._vykon = 0;

        <?php echo $id_div; ?>AreaGraf._odKdy = 0;
        <?php echo $id_div; ?>AreaGraf._doKdy = 0;

        <?php echo $id_div; ?>AreaGraf._dataPoints0 = [];
        <?php echo $id_div; ?>AreaGraf._dataPoints1 = [];

        CreateNewGraf(<?php echo $id_div; ?>AreaGraf);
<?php
    }
?>