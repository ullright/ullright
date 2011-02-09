<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(5, new lime_output_color);

$t->diag('encrypt and then decrypt, compare strings');

  $crypt = ullCrypt::getInstance();
  $testString = '  test string ';
  
  $ciphertext = $crypt->encrypt($testString);
  $cleartext = $crypt->decrypt($ciphertext);
  
  $t->ok(strcmp($testString, $cleartext) === 0);
  
$t->diag('encrypt test string again, ciphertext must be different due to random IV');

  $secondCiphertext = $crypt->encrypt($testString);
  
  $t->ok(!(strcmp($ciphertext, $secondCiphertext) === 0));
  
$t->diag('but it still has to decode to the same cleartext');

  $secondCleartext = $crypt->decrypt($secondCiphertext);
  
  $t->ok(strcmp($testString, $secondCleartext) === 0);
  
  
$t->diag('test modified hmac');

  $length = strlen($ciphertext);
  $lastChar = $ciphertext[$length - 1];
  $ciphertext[$length - 1] = chr(ord($lastChar) + 1);

  try
  {
    $crypt->decrypt($ciphertext);
    $t->fail('Invalid HMAC not detected');
  }
  catch (ullSecurityNotGenuineException $e)
  {
    $t->pass('Invalid HMAC detected');
  }
  
  
$t->diag('test modified message (resulting in invalid hmac)');

  $firstChar = $secondCiphertext[0];
  $secondCiphertext[0] = chr(ord($firstChar) + 1);

  try
  {
    $crypt->decrypt($secondCiphertext);
    $t->fail('Invalid HMAC not detected');
  }
  catch (ullSecurityNotGenuineException $e)
  {
    $t->pass('Invalid HMAC detected');
  }