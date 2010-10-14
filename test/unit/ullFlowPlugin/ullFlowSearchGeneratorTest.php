<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

class myTestCase extends sfDoctrineTestCase
{
}

/*
 * Note that this test is only responsible for the additions the
 * ullFlowSearchGenerator class provides to the ullSearchGenerator.
 */

$t = new myTestCase(7, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('ullFlowSearchGeneratorTest - fields');

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'my_priority';
$sfe->isVirtual = true;
$sfe->uuid = 0;
$sfeArray[] = $sfe;

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'my_information_update';
$sfe->isVirtual = true;
$sfe->uuid = 1;
$sfeArray[] = $sfe;

$flowApp = Doctrine::getTable('UllFlowApp')->find(1);
$fieldNames = array('standard_0_1', 'foreign_0_0');
$widgetClassNames = array('sfWidgetFormInput', 'sfWidgetFormSelect');
$labels = array('My information update', 'Priority');

$searchConfig = ullSearchConfig::loadSearchConfig('ullFlowDoc', $flowApp);
$searchGenerator = new ullFlowSearchGenerator($searchConfig->getAllSearchableColumns(), 'UllFlowDoc', $flowApp);
$searchGenerator->reduce($sfeArray);

$searchGenerator->buildForm();

$form = $searchGenerator->getForm();
$positions = $form->getWidgetSchema()->getPositions();

$t->is(count($sfeArray), count($positions), 'result field count is ok');

for ($i = 0; $i < count($positions); $i++)
{
  $currentPosition = current($positions);
  $formField = $form->offsetGet($currentPosition);
  next($positions);

  $t->is($formField->getName(), $fieldNames[$i], 'field name is ok');
  $t->isa_ok($formField->getWidget(), $widgetClassNames[$i], 'widget class name is ok');
  $t->is($formField->renderLabelName(), $labels[$i], 'label ok');
}