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
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('I18N');

$t = new myTestCase(7, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$tests = Doctrine::getTable('TestTable')->findAll();

$t->begin('__construct()');

  $tableTool = new myGenerator;
  $t->isa_ok($tableTool, 'myGenerator', '__construct() returns the correct object');
  $t->is($tableTool->getDefaultAccess(), 'r', '__construct() sets the default access to "r"');
  
  try
  {
    new myGenerator('x');
    $t->fail('__construct() doesn\'t throw an exception if an invalid access type is given');
  }
  catch(Exception $e)
  {
    $t->pass('__construct() throws an exception if an invalid access type is given');
  }
  
  $tableTool = new myGenerator('w');
  $t->is($tableTool->getDefaultAccess(), 'w', '__construct() sets the correct access type "w"');
  
$t->begin('getForm() without calling buildForm()');
  try
  {
    $tableTool->getForm();
    $t->fail('__construct() doesn\'t throw an exception although buildForm() wasn\'t called yet');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an exception because buildForm() wasn\'t called yet');
  }
  
$t->begin('static function getDefaultCultures()');
  $t->is(array('en',), myGenerator::getDefaultCultures(), 'returns the correct culture');
  sfContext::getInstance()->getUser()->setCulture('de');
  $t->is(array('en', 'de'), myGenerator::getDefaultCultures(), 'returns the correct cultures');