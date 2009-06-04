<?php

include dirname(__FILE__) . '/../../../bootstrap/unit.php';

sfContext::createInstance($configuration);

$t = new lime_test(3, new lime_output_color);

$t->diag('ullFlowDocSearchConfig - blacklist');

$searchConfig = new ullFlowDocSearchConfig();

$expectedFieldCount = count(Doctrine::getTable('UllFlowDoc')->getFieldNames());
$expectedFieldCount -= count($searchConfig->getBlacklist());
$t->is(count($searchConfig->getAllSearchableColumns()), $expectedFieldCount, 'blacklisted field count ok');

$t->diag('ullFlowDocSearchConfig - default fields');

$expectedFields = array('subject', 'priority');

$defaultSfe = $searchConfig->getDefaultSearchColumns();
$t->is(count($defaultSfe), 2, 'default field count ok');

foreach ($defaultSfe as $sfe)
{
  if (array_search($sfe->columnName, $expectedFields) === false)
  {
    $t->fail('encountered an unexpected default field: ' . $sfe->columnName);
  }
}
$t->pass('default fields ok');
