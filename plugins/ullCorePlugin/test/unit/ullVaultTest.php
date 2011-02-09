<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(3, new lime_output_color);

$t->diag('key size tests');

  $keyRing = ullVault::getCryptographyKeys(512, 256);
  $mainKey = $keyRing['mainKey'];
  $hashKey = $keyRing['hashKey'];
  $t->is(strlen($mainKey), 512, 'key size is correct');
  $t->is(strlen($hashKey), 256, 'hash key size is correct');
  
  //the following test could fail even for correct keys,
  //but the chances for that are very, very, VERY small.
  $t->ok(!(strcmp(substr($mainKey, 0, 256), $hashKey) === 0), 'main key is not hash key');
  
  //additional tests for loadCryptographyKeyFromFile()?
  //the tests above cover loading somewhat