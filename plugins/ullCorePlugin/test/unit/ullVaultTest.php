<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(2, new lime_output_color);

$t->diag('key size tests');

  $key = ullVault::getCryptographyKey(); 
  $t->is(strlen($key), 1192, 'key is not cut');
  
  $key = ullVault::getCryptographyKey(128); 
  $t->is(strlen($key), 128, 'key is cut correctly');
  
  //additional tests for loadCryptographyKeyFromFile()?
  //the tests above cover loading somewhat