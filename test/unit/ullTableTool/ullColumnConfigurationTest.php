<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(3, new lime_output_color, $configuration);

$cc = new ullColumnConfiguration;
$cc->setAccess('r');

$t->diag('__construct()');
  $t->isa_ok($cc, 'ullColumnConfiguration', 'creates the correct object');
  
  
$t->diag('disable() / isActive()');
  $t->is($cc->isActive(), true, 'Column is active');
  $cc->disable();
  $t->is($cc->isActive(), false, 'After calling disable() the column is not active');
  