<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(13, new lime_output_color, $configuration);

$cc = new ullColumnConfiguration;
$cc->setAccess('r');

$t->diag('__construct()');
  $t->isa_ok($cc, 'ullColumnConfiguration', 'creates the correct object');
  
  
$t->diag('disable() / isActive()');
  $t->is($cc->isActive(), true, 'Column is active');
  $cc->disable();
  $t->is($cc->isActive(), false, 'After calling disable() the column is not active');
  
$t->diag('set/getSection()');
  $t->is($cc->getSection(), '', 'empty per default');
  $cc->setSection('my_section');
  $t->is($cc->getSection(), 'my_section', 'Returns the correct section');

$t->diag('set/getInjectIdentifier()');
  $t->is($cc->getInjectIdentifier(), false, 'false per default');
  $cc->setInjectIdentifier(true);
  $t->is($cc->getInjectIdentifier(), true, 'true when set');

$t->diag('set/getIsArtificial()');
  $t->is($cc->getIsArtificial(), false, 'false per default');
  $cc->setIsArtificial(true);
  $t->is($cc->getIsArtificial(), true, 'true when set');

$t->diag('set/getIsAutoRender()');
  $t->is($cc->getAutoRender(), true, 'true per default');
  $cc->setAutoRender(false);
  $t->is($cc->getAutoRender(), false, 'false when set');
  
$t->diag('set/getCalculateSum()');
  $t->is($cc->getCalculateSum(), false, 'false per default');
  $cc->setCalculateSum(true);
  $t->is($cc->getCalculateSum(), true, 'true when set');  