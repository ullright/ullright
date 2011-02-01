<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(5, new lime_output_color);

$t->diag('isSecureParameter()');
  $t->is(ullSecureParameter::isSecureParameter('id'), false, 'common param name ok');
  $t->is(ullSecureParameter::isSecureParameter('s_'), false, 'invalid param name ok');
  $t->is(ullSecureParameter::isSecureParameter('s_id'), true, 'secure param name ok');
  
$t->diag('encryptParameter() and decryptParameter()');
  $testString = ' test_string+=   ';
  $encryptedParam = ullSecureParameter::encryptParameter($testString);
  $decryptedParam = ullSecureParameter::decryptParameter($encryptedParam);
  
  $t->is($testString, $decryptedParam, 'encrypt/decrypt string ok');
  
  
  //TODO: add support to ullSecureParameter to support this test case
  //without serialization
  $testArray = array('foo' => array('bar' => 'fish'));
  $encryptedParam = ullSecureParameter::encryptParameter(serialize($testArray));
  $decryptedParam = unserialize(ullSecureParameter::decryptParameter($encryptedParam));
  
  $t->is_deeply($testArray, $decryptedParam, 'encrypt/decrypt deep array ok');