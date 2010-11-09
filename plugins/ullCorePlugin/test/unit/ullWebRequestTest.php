<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}

$t = new myTestCase(1, new lime_output_color, $configuration);

$t->diag('parseSubmitName()');

  $_POST = array(
    'module'            => 'ullWiki',
    'action'            => 'edit',
    'fields'            => array('my_field' => 'my_value'),
    'filter[search]'    => 'blabla',
    'submit|save_mode=save_only|external=upload|external_field=10'  => 'Save only',
    'submit|save_mode=reject' => '',
  );
  
  $request = new ullWebRequest(new sfEventDispatcher);

  $reference = array(
    'module'            => 'ullWiki',
    'action'            => 'edit',
    'fields'            => array('my_field' => 'my_value'),
    'filter'    => array('search' => 'blabla'),
    'save_mode'         => 'save_only',
    'external'          => 'upload',
    'external_field'    => 10,
  ); 
  
  $t->is($request->getParameterHolder()->getAll(), $reference, 'returns the correct value');  
  