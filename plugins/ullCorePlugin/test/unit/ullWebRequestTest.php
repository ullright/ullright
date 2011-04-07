<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}

$t = new myTestCase(3, new lime_output_color, $configuration);

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
    's_id' => 'ZEF3_XppB59Mh60KN49ACGF4fXJFzrNdw01Q1gEs-GZOKB0FV2Z99mHhK1vqWd4kwc7LtJRYp8hXobxUEUbVsDmm0IKU2qFwaCpd2T2DUXKKqmUO'
  );
  
  $request = new ullWebRequest(new sfEventDispatcher);

  $reference = array(
    's_id'    => 1
  ); 
  
  $t->is_deeply($request->getParameterHolder()->getAll(), $reference, 'decryptSecureParameters() correct');  

$t->diag('fixDotCharacter()');

  $_POST = array(
    'one' => '&#x2E;',
    'two' => array(
      'three' => '&#x2E;&#x2E;',
      'four' => '&#x2E;four&#x2E;four&#x2E;',
      'five' => array(
        'six' => 'six&#x2E;'
     )
    )
  );

  $request = new ullWebRequest(new sfEventDispatcher);

  $reference = array(
    'one' => '.',
    'two' => array(
      'three' => '..',
      'four' => '.four.four.',
      'five' => array(
        'six' => 'six.'
     )
    )
  ); 
  
  $t->is_deeply($request->getParameterHolder()->getAll(), $reference, 'fixDotCharacter() correct');  
  