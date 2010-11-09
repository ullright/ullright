<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);

$t = new lime_test(3, new lime_output_color);

$t->diag('ullFlowDocSearchConfig - blacklist');

$searchConfig = ullSearchConfig::loadSearchConfig('ullFlowDoc');

$expectedFieldCount = count(Doctrine::getTable('UllFlowDoc')->getFieldNames());
$expectedFieldCount -= count($searchConfig->getBlacklist());
$t->is(count($searchConfig->getAllSearchableColumns()), $expectedFieldCount, 'blacklisted field count ok');

$t->diag('ullFlowDocSearchConfig - default fields');

$expectedFields = array('ull_project_id', 'priority', 'due_date', 'creator_user_id', 'updator_user_id');

$defaultSfe = $searchConfig->getDefaultSearchColumns();
$t->is(count($defaultSfe), 5, 'default field count ok');

foreach ($defaultSfe as $sfe)
{
  if (array_search($sfe->columnName, $expectedFields) === false)
  {
    $t->fail('encountered an unexpected default field: ' . $sfe->columnName);
  }
}
$t->pass('default fields ok');
