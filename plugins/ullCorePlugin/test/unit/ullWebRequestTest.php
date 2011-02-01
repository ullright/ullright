<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}

$t = new myTestCase(2, new lime_output_color, $configuration);

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
  
  $t->is_deeply($request->getParameterHolder()->getAll(), $reference, 'parseSubmitName() correct');  
 

$t->diag('decryptSecureParameters()');

  $_POST = array(
    's_id' => 'yB9VgzqqGmFppIQ3al8sZDcAn6S_-3mf3hnIWtnLfUWC'
  );
  
  $request = new ullWebRequest(new sfEventDispatcher);

  $reference = array(
    's_id'    => 2
  ); 
  
  $t->is_deeply($request->getParameterHolder()->getAll(), $reference, 'decryptSecureParameters() correct');  
  