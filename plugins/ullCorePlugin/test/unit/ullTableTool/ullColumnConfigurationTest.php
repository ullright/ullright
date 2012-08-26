<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new myTestCase(28, new lime_output_color, $configuration);

$cc = new ullColumnConfiguration();
$cc->setAccess('r');

$t->diag('__construct()');
  $t->isa_ok($cc, 'ullColumnConfiguration', 'creates the correct object');
  
  
$t->diag('disable() / isActive()');
  $t->is($cc->isActive(), true, 'Column is active');
  $cc->disable();
  $t->is($cc->isActive(), false, 'After calling disable() the column is not active');
  
$t->diag('set/getModelName()');
  $t->is($cc->getModelName(), '', 'empty per default');
  $cc->setModelName('my_model');
  $t->is($cc->getModelName(), 'my_model', 'Returns the correct model name');
  
$t->diag('set/getColumnsConfigClass()');
  $t->is($cc->getColumnsConfigClass(), '', 'empty per default');
  $cc->setColumnsConfigClass('my_ccc_class');
  $t->is($cc->getColumnsConfigClass(), 'my_ccc_class', 'Returns the correct columns config name');  
  
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
  
$t->diag('set/getIsRequired()');
  $t->is($cc->getIsRequired(), false, 'false per default');
  $cc->setIsRequired(true);
  $t->is($cc->getIsRequired(), true, 'true when set');

$t->diag('set/getIsSortable()');
  $t->is($cc->getIsSortable(), true, 'true per default');
  $cc->setIsSortable(false);
  $t->is($cc->getIsSortable(), false, 'false when set');
  
$cc = new ullColumnConfiguration('my_column');
$cc->setAccess('r');
$cc->setModelName('my_model');
  
$t->diag('set/getAjaxUpdate');
  $t->is($cc->getAjaxUpdate(), false, 'false per default');
  $cc->setAjaxUpdate(true);
  $t->is($cc->getAjaxUpdate(), true, 'true when set');
  $t->is($cc->getOption('enable_ajax_update'), true, 'ajax is enabled');
  $t->is($cc->getOption('ajax_model'), 'my_model', 'sets the correct ajax model');
  $t->is($cc->getOption('ajax_column'), 'my_column', 'sets the correct ajax column');
  $t->is($cc->getOption('ajax_url'), 'ullTableTool/updateSingleColumn', 'sets the correct ajax url');
  $t->is($cc->getInjectIdentifier(), true, 'activates identifier injection');
    
    