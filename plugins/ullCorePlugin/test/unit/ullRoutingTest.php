<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(2, new lime_output_color);

$t->diag('generate()');
  $routing = new ullRouting(new sfEventDispatcher());
  $routing->connect('test1', new sfRoute('/'));
  
  $route = $routing->generate('test1', array('s_id' => '5'));
  $t->isnt($route, '/?s_id=5', 'route param is not cleartext');

  $encryptedParam = substr($route, 7);
  $t->is(ullSecureParameter::decryptParameter($encryptedParam),
  	5, 'route param is correct');