<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
$instance = sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new myTestCase(23, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('getColumnConfig() - Initialization');
  $cc = Doctrine::getTable('TestTable')->getColumnsConfig();  
  $t->isa_ok($cc['my_email'], 'ullColumnConfiguration', 'returns an array of ullColumnConfiguration objects');
  
$t->diag('getColumnConfig() - applyCommonColumnConfigSettings');
  $t->is($cc['my_email']->getColumnName(), 'my_email', 'sets column name');
  $t->is($cc['my_email']->getAccess(), 'w', 'defaultAccess mode is set to "w" because of default action "edit"');
  $t->is($cc['creator_user_id']->getAccess(), 'r', 'access is set to "r" for defined readOnly columns');
  $t->is(isset($cc['namespace']), false, 'blacklisted columns are completely removed');
  $t->is(end($cc)->getColumnName(), 'updated_at', 'sorts columns to be in correct sequence');

$t->diag('getColumnConfig() - applyDoctrineColumnConfigSettings for "id"');
  $t->is($cc['id']->getAccess(), 'r', 'access is set to "r" for defined readOnly columns');
  $t->is($cc['id']->getMetaWidgetClassName(), 'ullMetaWidgetInteger', 'sets the correct metaWidget');
  $t->is($cc['id']->getValidatorOption('required'), true, 'sets the validator to "required" because of "notnull"');
  $t->is($cc['id']->getUnique(), true, 'sets "unique" flag');
  $t->is($cc['id']->getRelation(), false, 'no relation set');
  
$t->diag('getColumnConfig() - applyDoctrineColumnConfigSettings for "my_email"');
  $t->is($cc['my_email']->getMetaWidgetClassName(), 'ullMetaWidgetString', 'sets the correct metaWidget');
  $t->is($cc['my_email']->getWidgetAttribute('maxlength'), '64', 'sets the correct widget length');
  $t->is($cc['my_email']->getValidatorOption('max_length'), '64', 'sets the correct validator length');
  $t->is($cc['my_email']->getValidatorOption('required'), true, 'sets the validator to "required" because of "notnull"');
  $t->is($cc['my_email']->getUnique(), true, 'sets "unique" flag');
  
$t->diag('getColumnConfig() - applyDoctrineColumnConfigSettings for "ull_user_id"');
  $t->is($cc['ull_user_id']->getMetaWidgetClassName(), 'ullMetaWidgetUllEntity', 'sets the correct metaWidget');
  $t->is($cc['ull_user_id']->getOption('entity_classes'), array('UllUser'), 'sets the correct options');
  $t->is($cc['ull_user_id']->getRelation(), array('model' => 'UllUser', 'foreign_id' => 'id'), 'returns the correct relation settings');
  
$t->diag('getColumnConfig() - Label');  
  $t->is($cc['my_select_box']->getLabel(), 'My custom select box label', 'applies custom label set in applyCustomColumnConfigSettings()');
  $t->is($cc['my_email']->getLabel(), 'My email', 'returns the correct humanized label for a label not in humanizer dictionary');
  $t->is($cc['creator_user_id']->getLabel(), 'Created by', 'returns the correct humanized label for a label listed in humanizer dictionary');
  sfContext::getInstance()->getUser()->setCulture('de');
  $ccGerman = Doctrine::getTable('TestTable')->getColumnsConfig();
  $t->is($ccGerman['creator_user_id']->getLabel(), 'Erstellt von', 'returns the correct translated humanized label for a label listed in humanizer dictionary');
  

  
  
  
