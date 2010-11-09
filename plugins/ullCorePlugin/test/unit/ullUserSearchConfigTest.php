<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);

$t = new lime_test(3, new lime_output_color);

$t->diag('ullUserSearchConfig - blacklist');

$searchConfig = ullSearchConfig::loadSearchConfig('ullUser');
$manuallyAddedFieldCount = 1; //the groups

$expectedFieldCount = count(Doctrine::getTable('UllUser')->getFieldNames());
$expectedFieldCount -= count($searchConfig->getBlacklist());
$expectedFieldCount += $manuallyAddedFieldCount;

$t->is(count($searchConfig->getAllSearchableColumns()), $expectedFieldCount, 'blacklisted field count ok');
$t->diag('ullUserSearchConfig - default fields');

$expectedFields = array('ull_company_id', 'ull_department_id',
    'ull_location_id', 'ull_user_status_id',
    'ull_group_id', 'ull_employment_type_id',
    'ull_job_title_id', 'superior_ull_user_id');

$defaultSfe = $searchConfig->getDefaultSearchColumns();
$t->is(count($defaultSfe), 8, 'default field count ok');

foreach ($defaultSfe as $sfe)
{
  if (array_search($sfe->columnName, $expectedFields) === false)
  {
    $t->fail('encountered an unexpected default field');
  }
}
$t->pass('default fields ok');
