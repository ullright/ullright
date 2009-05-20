<?php

include dirname(__FILE__) . '/../../../bootstrap/unit.php';

sfContext::createInstance($configuration);

$t = new lime_test(3, new lime_output_color);

$t->diag('ullUserSearchConfig - blacklist');

$searchConfig = new ullUserSearchConfig();

$expectedFieldCount = count(Doctrine::getTable('UllUser')->getFieldNames());
$expectedFieldCount -= count($searchConfig->getBlacklist());
$t->is(count($searchConfig->getAllSearchableColumns()), $expectedFieldCount, 'field count ok');

$t->diag('ullUserSearchConfig - default fields');

$expectedFields = array('last_name', 'ull_department_id', 'ull_location_id', 'ull_user_status_id');

$defaultSfe = $searchConfig->getDefaultSearchColumns();
$t->is(count($defaultSfe), 4, 'field count ok');

foreach ($defaultSfe as $sfe)
{
  if (array_search($sfe->columnName, $expectedFields) === false)
  {
    $t->fail('encountered an unexpected default field');
  }
}
$t->pass('default fields ok');
