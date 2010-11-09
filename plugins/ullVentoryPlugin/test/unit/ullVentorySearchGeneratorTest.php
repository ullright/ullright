<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));

/*
 * Note that this test is only responsible for the additions the
 * ullVentorySearchGeneratorTest class provides to the ullSearchGenerator.
 */

$t = new myTestCase(13, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('ullVentorySearchGeneratorTest - fields');

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'display-size';
$sfe->isVirtual = true;
$sfe->uuid = 0;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'delivery-date';
$sfe->isVirtual = true;
$sfe->uuid = 1;
$sfeArray[] = $sfe;

$fieldNames = array('rangeFrom_0_0', 'rangeTo_0_0', 'rangeDateFrom_0_1', 'rangeDateTo_0_1');
$widgetClassNames = array('ullWidgetFloatWrite', 'ullWidgetFloatWrite', 'ullWidgetDateWrite', 'ullWidgetDateWrite');
$labels = array('Attribute - Display size', 'RangeTo 0 0', 'Attribute - Delivery date', 'RangeDateTo 0 1');

$searchConfig = ullSearchConfig::loadSearchConfig('ullVentoryItem');
$searchGenerator = new ullVentorySearchGenerator($searchConfig->getAllSearchableColumns(), 'UllVentoryItem');
$searchGenerator->reduce($sfeArray);

$searchGenerator->buildForm();

$form = $searchGenerator->getForm();
$positions = $form->getWidgetSchema()->getPositions();

//+2 because there are two range fields
$t->is(count($sfeArray) + 2, count($positions), 'result field count is ok');

for ($i = 0; $i < count($positions); $i++)
{
  $currentPosition = current($positions);
  $formField = $form->offsetGet($currentPosition);
  next($positions);

  $t->is($formField->getName(), $fieldNames[$i], 'field name is ok');
  $t->isa_ok($formField->getWidget(), $widgetClassNames[$i], 'widget class name is ok');
  $t->is($formField->renderLabelName(), $labels[$i], 'label ok');
}