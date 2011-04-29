<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('ull');
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(30, new lime_output_color);

$sfeArray = array();

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'last_name';
$sfe->multipleCount = 2;
$sfe->uuid = 0;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'ull_department_id';
$sfe->uuid = 1;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'ull_user_status_id';
$sfe->uuid = 3;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'is_show_extension_in_phonebook';
$sfe->uuid = 4;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'id';
$sfe->uuid = 5;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'first_name';
$sfe->uuid = 6;
$sfeArray[] = $sfe;

$sfeWithRelations = new ullSearchFormEntry();
$sfeWithRelations->relations[] = 'Creator';
$sfeWithRelations->relations[] = 'UllLocation';
$sfeWithRelations->columnName = 'country';
$sfeWithRelations->uuid = 7;
$sfeArray[] = $sfeWithRelations;

$fieldNames = array('rangeFrom_0_5', 'rangeTo_0_5',
  'standard_0_6',
  'standard_0_0', 'standard_1_0',
  'foreign_0_1',
  'boolean_0_4',
  'foreign_0_3',
  'foreign_0_7');
$widgetClassNames = array('ullWidgetFormInput', 'ullWidgetFormInput',
  'sfWidgetFormInput',
  'sfWidgetFormInput', 'sfWidgetFormInput',
  'ullWidgetFormDoctrineChoice',
  'sfWidgetFormSelect',
  'sfWidgetFormSelectWithOptionAttributes',
  'sfWidgetFormI18nChoiceCountry');
$labels = array('ID', 'RangeTo 0 5',
  'First name',
  'Last name', 'Last name',
  'Department',
  'Show phone ext. in phone book',
  'Status',
  'Created by - Location - Country');

$t->diag('ullSearchGeneratorTest - basic');

$searchConfig = ullSearchConfig::loadSearchConfig('ullUser');
$searchGenerator = new ullSearchGenerator(array_merge($searchConfig->getAllSearchableColumns(), array($sfeWithRelations)), 'UllUser');

$searchGenerator->reduce($sfeArray);

$t->is($searchGenerator->getColumnLabel('ull_user_status_id'), 'Status', 'getColumnLabel ok');
$t->is($searchGenerator->getColumnLabel('is_show_extension_in_phonebook'), 'Show phone ext. in phone book', 'getColumnLabel ok');

$searchGenerator->buildForm();

$form = $searchGenerator->getForm();
$positions = $form->getWidgetSchema()->getPositions();

$t->diag('ullSearchGeneratorTest - fields');

//+2 because multiple count of last_name is 2 and id is a range field
$t->is(count($sfeArray) + 2, count($positions), 'result field count is ok');


for ($i = 0; $i < count($positions); $i++)
{
  $currentPosition = current($positions);
  $formField = $form->offsetGet($currentPosition);
  next($positions);

  $t->is($formField->renderLabelName(), $labels[$i], "* * * \n" . 'label ' . $labels[$i] . ' ok');
  $t->is($formField->getName(), $fieldNames[$i], 'field name ' . $fieldNames[$i] . ' is ok');
  $t->isa_ok($formField->getWidget(), $widgetClassNames[$i], 'widget class name ' . $widgetClassNames[$i] . ' is ok');
  
}