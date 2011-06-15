<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

$instance = sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(3, new lime_output_color(), $configuration);


$t->diag('__construct()');
  $w = new ullWidgetDateWrite(array(
    'year_range' => '1950:+10',
    'min_date' => '-3y +2d',
    'max_date' => 'new Date(733755250000)',
    'default_date' => '-28y'));
  $t->isa_ok($w, 'ullWidgetDateWrite', 'returns the correct object');
  
	$t->diag('->render()');
	$now = time();
	$dateTime = new DateTime();
  $utcNow = strtotime(date('Y-m-d', $now)) + $dateTime->getOffset();
  
  $expected = '<input name="foo" id="foo" type="text" value="' . date('Y-m-d', $now) . '" />
<script type="text/javascript">
//<![CDATA[


$(function() {
  $("#foo").datepicker({
    changeYear: true,
    yearRange: "1950:+10",
    changeMonth: true,
    firstDay: 1,
    showOn: \'button\',
    minDate: \'-3y +2d\', maxDate: new Date(733755250000), defaultDate: \'-28y\', 
  }); 
  
  $("#foo").datepicker("setDate", new Date('. ($utcNow * 1000) . '));
});

  // KU 2011-06-01 what is this?
  foo_initial_date = \'' . ull_format_date(NULL, true) .  '\';


//]]>
</script>';  
  
  $t->is($w->render('foo', date('Y-m-d', $now)), $expected, 'Renders the correct html output');
  
  $instance->getUser()->setCulture("de");
  
  $expected = '<input name="foo" id="foo" type="text" value="' . date('Y-m-d', $now) . '" />
<script type="text/javascript">
//<![CDATA[


$(function() {
  $("#foo").datepicker({
    changeYear: true,
    yearRange: "1950:+10",
    changeMonth: true,
    firstDay: 1,
    showOn: \'button\',
    minDate: \'-3y +2d\', maxDate: new Date(733755250000), defaultDate: \'-28y\', 
  }); 
  
  $("#foo").datepicker("setDate", new Date('. ($utcNow * 1000) . '));
});

  // KU 2011-06-01 what is this?
  foo_initial_date = \'' . ull_format_date(NULL, true) .  '\';


//]]>
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
  
  $t->is($w->render('foo', date('Y-m-d', $now)), $expected, 'Renders the correct html code for culture "de"');
