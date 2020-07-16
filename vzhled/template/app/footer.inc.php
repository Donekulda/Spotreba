
<!--[if IE]> čast kodu připravená pro budoucí čast na cookies, tykající se GDPR
  <div id="cookieConsent">
    <div id="closeCookieConsent">x</div>
    This website is using cookies. <a href="#" target="_blank">More info</a>. <a class="cookieConsentOK">That's Fine</a>
  </div>
<![endif]-->

<script>
    $.datepicker.regional['cs'] = { 
                  closeText: 'Hotovo', 
                  prevText: 'Předchozí', 
                  nextText: 'Další', 
                  currentText: 'Hoy', 
                  monthNames: ['Leden','Únor','Březen','Duben','Květen','Červen', 'Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
                  //monthNamesShort: ['Leden','Únor','Březen','Duben','Květen','Červen', 'Červenec','Srpen','Září','Říjen','Listopad','Prosinec'], 
                  monthNamesShort: ['Led','Ún','Břez','Dub','Květ','Červ', 'Čec','Srp','Zář','Říj','List','Pros'], 
                  dayNames: ['Neděle','Pondělí','Úterý','Středa','Čtvrtek','Pátek','Sobota'], 
                  dayNamesShort: ['Ne','Po','Út','St','Čt','Pá','So',], 
                  dayNamesMin: ['Ne','Po','Út','St','Čt','Pá','So'], 
                  weekHeader: 'Sm', 
                  dateFormat: 'dd.mm.yy', 
                  firstDay: 1, 
                  isRTL: false, 
                  showMonthAfterYear: false, 
                  yearSuffix: ''
                  }; 

    $.datepicker.regional['sk'] = {
                  closeText: 'Zavrieť',
                  prevText: '<Predchádzajúci',
                  nextText: 'Nasledujúci>',
                  currentText: 'Dnes',
                  monthNames: ['Január','Február','Marec','Apríl','Máj','Jún',
                  'Júl','August','September','Október','November','December'],
                  monthNamesShort: ['Jan','Feb','Mar','Apr','Máj','Jún',
                  'Júl','Aug','Sep','Okt','Nov','Dec'],
                  dayNames: ['Nedel\'a','Pondelok','Utorok','Streda','Štvrtok','Piatok','Sobota'],
                  dayNamesShort: ['Ned','Pon','Uto','Str','Štv','Pia','Sob'],
                  dayNamesMin: ['Ne','Po','Ut','St','Št','Pia','So'],
                  weekHeader: 'Ty',
                  dateFormat: 'dd.mm.yy',
                  firstDay: 0,             
                  isRTL: false,
                  showMonthAfterYear: false,
                  yearSuffix: ''
                  };

    $.datepicker.setDefaults($.datepicker.regional['cs']);
    </script>
      <script src="../lib/popper.js/popper.js"></script>
      <script src="../lib/bootstrap/bootstrap.js"></script>
      <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
      <script src="../lib/d3/d3.js"></script>
      <script src="../lib/rickshaw/rickshaw.min.js"></script>
      <script src="../lib/jquery-toggles/toggles.min.js"></script>
      <script src="../lib/select2/js/select2.min.js"></script>

      <script src="../js/amanda.js"></script>
      <script src="../js/ResizeSensor.js"></script>

        <div class="am-footer">
            <span>Copyright &copy;. Aplikace Spotřeba Solární Výroba.</span>
            <span>App created by: Aeko s.r.o.</span>
        </div>
        <!-- am-footer -->
    </div>
    <!-- am-mainpanel -->

  </body>
</html>
<?php
  $_SESSION['connection']->close();
?>