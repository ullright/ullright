<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);

$t = new lime_test(7, new lime_output_color);

$v = new ullValidatorEmail();

$t->diag('valid email');
foreach (array(
  'example@example.com',
  'example@localhost') as $validMail)
{
  $t->is($v->clean($validMail), $validMail, 'detects valid email address');
}

$t->diag('invalid email');
foreach (array(
  '.example@example.com',
  'example.@example.com',
  'rotkäppchen@example.com',
  'e,example@example.com',
  'e.example´@example.com') as $invalidMail)
{
  try
  {
    $v->clean($invalidMail);
    $t->fail('does NOT detect invalid email address');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('detects invalid email address');
  }
}