<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('ull'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(3, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);

$t->diag('__construct()');
  $w = new ullWidgetDateWrite();
  $t->isa_ok($w, 'ullWidgetDateWrite', 'returns the correct object');
  
	$t->diag('->render()');
	$now = time();  
	
	$expected = '
    <script type="text/javascript">
    $(function() {
     $("#foo").datepicker({
        changeYear: true,
        changeMonth: true,
        firstDay: 1,
     });$("#foo").datepicker("setDate", new Date('. ($now * 1000) . '));});
    </script>';
  $expected .= '<input name="foo" id="foo" type="text" value="' . date('c', $now) . '" />';
	

  $t->is($w->render('foo', date('c', $now)), $expected);
  
  $instance->getUser()->setCulture("de");
  
  $expected = '
    <script type="text/javascript">
    $(function() {
     $("#foo").datepicker({
        changeYear: true,
        changeMonth: true,
        firstDay: 1,
     });$("#foo").datepicker("setDate", new Date('. ($now * 1000) . '));});
    </script><script type="text/javascript">$.datepicker.regional[\'de\'] = {clearText: \'löschen\', clearStatus: \'aktuelles Datum löschen\',
            closeText: \'schließen\', closeStatus: \'ohne Änderungen schließen\',
            prevText: \'&#x3c;zurück\', prevStatus: \'letzten Monat zeigen\',
            nextText: \'Vor&#x3e;\', nextStatus: \'nächsten Monat zeigen\',
            currentText: \'heute\', currentStatus: \'\',
            monthNames: [\'Januar\',\'Februar\',\'März\',\'April\',\'Mai\',\'Juni\',
            \'Juli\',\'August\',\'September\',\'Oktober\',\'November\',\'Dezember\'],
            monthNamesShort: [\'Jan\',\'Feb\',\'Mär\',\'Apr\',\'Mai\',\'Jun\',
            \'Jul\',\'Aug\',\'Sep\',\'Okt\',\'Nov\',\'Dez\'],
            monthStatus: \'anderen Monat anzeigen\', yearStatus: \'anderes Jahr anzeigen\',
            weekHeader: \'Wo\', weekStatus: \'Woche des Monats\',
            dateFormat: \'dd.mm.yy\',
            dayNames: [\'Sonntag\',\'Montag\',\'Dienstag\',\'Mittwoch\',\'Donnerstag\',\'Freitag\',\'Samstag\'],
            dayNamesShort: [\'So\',\'Mo\',\'Di\',\'Mi\',\'Do\',\'Fr\',\'Sa\'],
            dayNamesMin: [\'So\',\'Mo\',\'Di\',\'Mi\',\'Do\',\'Fr\',\'Sa\'],
            dayStatus: \'Setze DD als ersten Wochentag\', dateStatus: \'Wähle D, M d\',
            initStatus: \'Wähle ein Datum\', isRTL: false};
            $.datepicker.setDefaults($.datepicker.regional[\'de\']);</script>';
  $expected .= '<input name="foo" id="foo" type="text" value="' . date('c', $now) . '" />';
  
  $t->is($w->render('foo', date('c', $now)), $expected);
