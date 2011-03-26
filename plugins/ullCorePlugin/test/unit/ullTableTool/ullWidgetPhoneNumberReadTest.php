<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

$t = new lime_test(5, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'Escaping'));

$w = new ullWidgetPhoneNumberRead();

$t->diag('render()');
  $t->is(
    $w->render('my_field_name', '+43 664 1235678'), 
    '<a href="tel:+436641235678">+43 664 1235678</a>',  
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
    '<a href="tel:+436641235678">0664 1235678</a>',  
    'Returns the correct value for option show_local_short_form.'
  );
  $t->is(
    $w->render('my_field_name', ''), 
    '',  
    'Returns the correct value for option show_local_short_form.'
  );

  $w = new ullWidgetPhoneNumberRead(array('click_to_dial' => false));
  $t->is(
    $w->render('my_field_name', '+43 664 1235678'), 
    '+43 664 1235678',  
    'Returns the correct value without dial link'
  );
 
