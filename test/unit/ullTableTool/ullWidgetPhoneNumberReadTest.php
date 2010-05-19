<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('Escaping'));

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$w = new ullWidgetPhoneNumberRead();

$t->diag('render()');
  $t->is(
    $w->render('my_field_name', '+43 664 1235678'), 
    '+43 664 1235678',  
    'Returns the correct value.'
  );
  $t->is(
    $w->render('my_field_name', ''), 
    '',  
    'Returns the correct value.'
  );
  $w = new ullWidgetPhoneNumberRead(array('show_local_short_form' => true));
  $t->is(
    $w->render('my_field_name', '+43 664 1235678'), 
    '0664 1235678',  
    'Returns the correct value for option show_local_short_form.'
  );
  $t->is(
    $w->render('my_field_name', ''), 
    '',  
    'Returns the correct value for option show_local_short_form.'
  );
 
