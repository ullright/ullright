<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase {}

class myGenerator extends ullGenerator
{
  public function buildTableConfig() {}
  public function buildColumnsConfig() {}
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');
sfContext::getInstance()->getRequest()->setParameter('action', 'list');

$t = new myTestCase(10, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->begin('__construct()');

  $generator = new myGenerator;
  $t->isa_ok($generator, 'myGenerator', 'returns the correct object');
  $t->is($generator instanceof ullGeneratorBase, true, 'Is instance of ullGeneratorBase');
  $t->is($generator instanceof ullGenerator, true, 'Is instance of ullGenerator');

$t->diag('getForm() without calling buildForm()');
  try
  {
    $generator->getForm();
    $t->fail('__construct() doesn\'t throw an exception although buildForm() wasn\'t called yet');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception because buildForm() wasn\'t called yet');
  }
  
  
$t->diag('static function getDefaultCultures()');
  $t->is(array('en',), myGenerator::getDefaultCultures(), 'returns the correct culture');
  sfContext::getInstance()->getUser()->setCulture('de');
  $t->is(array('en', 'de'), myGenerator::getDefaultCultures(), 'returns the correct cultures');

  
$t->diag('set/getAllowDelete()');
  $t->is($generator->getAllowDelete(), true, 'Returns true per default');  
  $generator->setAllowDelete(false);
  $t->is($generator->getAllowDelete(), false, 'Returns false');
  
  
$t->diag('set/getFormClassName()');
  $t->is($generator->getFormClassName(), 'ullGeneratorForm', 'Returns the correct Form');  
  $generator->setFormClassName('testTableForm');
  $t->is($generator->getFormClassName(), 'testTableForm', 'Returns the correct Form');  