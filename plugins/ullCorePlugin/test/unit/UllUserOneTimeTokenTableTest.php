<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(10, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('create one time token for password reset');

  $tokens = Doctrine::getTable('UllUserOneTimeToken')->findAll();
  $t->is(count($tokens), 0, 'No token at the moment');

  $token = UllUserOneTimeTokenTable::createToken(2);
  
  $tokens = Doctrine::getTable('UllUserOneTimeToken')->findAll();
  $t->is(count($tokens), 1, 'One token created');
  
  $token = $tokens->getFirst();
  $t->is($token['ull_user_id'], 2, 'Sets the correct user_id');
  $t->is((boolean) $token['token'], true, 'The token was created');
  $t->is(strlen($token['token']), 16, 'The token has the correct length');
  $t->is($token['is_used_up'], false, 'The token was not used yet');
  
$t->diag('use up one time token');

  $t->is(UllUserOneTimeTokenTable::isValidAndUseUp($token, 2), true, 'Token is valid, was not used yet');
  
  $tokens = Doctrine::getTable('UllUserOneTimeToken')->findAll();
  $t->is(count($tokens), 1, 'Still only one token');  
  
  $token = $tokens->getFirst();
  $t->is($token['is_used_up'], true, 'The token was used up now');

  $t->is(UllUserOneTimeTokenTable::isValidAndUseUp($token, 2), false, 'Token was already used');
  
