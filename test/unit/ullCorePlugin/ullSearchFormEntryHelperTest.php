<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(27, new lime_output_color);

$t->diag('ullSearchFormEntryHelper');

$searchConfig = new ullUserSearchConfig();
$sfe = $searchConfig->getDefaultSearchColumns();

$fields = array(
  'rangeFrom_0_5' => 2,
  'rangeTo_0_5' => 5,
  'standard_0_0' => 'testString    testString2',
  'standard_1_0' => '',
  'not_foreign_0_1' => '',
  'foreign_0_1' => '2',
  'boolean_0_4' => true,
  'not_foreign_0_3' => '',
  'foreign_0_3' =>  '1',
  'foreign_1_3' =>  '',
  'standard_0_6' => '"testString"'
);

$sfeArray = array();

$sfe = new ullSearchFormEntry();
$sfe->columnName = 'last_name';
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
$sfe->columnName = 'is_show_fax_extension_in_phonebook';
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

$criterionGroups = ullSearchFormEntryHelper::transformFieldsToCriteriaGroups($fields, $sfeArray);

$t->is(count($criterionGroups), 6, 'cg count ok');

$criterion = $criterionGroups[0]->subCriteria[0];
$t->isa_ok($criterion, 'ullSearchCompareCriterion', 'cg 1 - class name ok');
$t->is($criterion->compareValue, array('testString', 'testString2'), 'cg 1 - compare value ok');
$t->is($criterion->columnName, 'last_name', 'cg 1 - column name ok');
$t->is($criterion->isNot, false, 'cg 1 - isNot ok');

$criterion = $criterionGroups[1]->subCriteria[0];
$t->isa_ok($criterion, 'ullSearchForeignKeyCriterion', 'cg 2 - class name ok');
$t->is($criterion->compareValue, 2, 'cg 2 - compare value ok');
$t->is($criterion->columnName, 'ull_department_id', 'cg 2 - column name ok');
$t->is($criterion->isNot, true, 'cg 2 - isNot ok');

$criterion = $criterionGroups[3]->subCriteria[0];
$t->isa_ok($criterion, 'ullSearchForeignKeyCriterion', 'cg 3 - class name ok');
$t->is($criterion->compareValue, 1, 'cg 3 - compare value ok');
$t->is($criterion->columnName, 'ull_user_status_id', 'cg 3 - column name ok');
$t->is($criterion->isNot, true, 'cg 3 - isNot ok');

$criterion = $criterionGroups[4]->subCriteria[0];
$t->isa_ok($criterion, 'ullSearchBooleanCriterion', 'cg 4 - class name ok');
$t->is($criterion->compareValue, 1, 'cg 4 - compare value ok');
$t->is($criterion->columnName, 'is_show_fax_extension_in_phonebook', 'cg 4 - column name ok');
$t->is($criterion->isNot, false, 'cg 4 - isNot ok');

$criterion = $criterionGroups[5]->subCriteria[0];
$t->isa_ok($criterion, 'ullSearchRangeCriterion', 'cg 5 - class name ok');
$t->is($criterion->fromValue, 2, 'cg 5 - from value ok');
$t->is($criterion->toValue, 5, 'cg 5 - to value ok');
$t->is($criterion->columnName, 'id', 'cg 5 - column name ok');
$t->is($criterion->isNot, false, 'cg 5 - isNot ok');

$criterion = $criterionGroups[6]->subCriteria[0];
$t->isa_ok($criterion, 'ullSearchCompareExactCriterion', 'cg 6 - class name ok');
$t->is($criterion->compareValue, 'testString', 'cg 6 - compare value ok');
$t->is($criterion->columnName, 'first_name', 'cg 6 - column name ok');
$t->is($criterion->isNot, false, 'cg 6 - isNot ok');

try {
  $fields['standard_0_9999'] = 'stone';
  $criterionGroups = ullSearchFormEntryHelper::transformFieldsToCriteriaGroups($fields, $sfeArray);
  $t->fail('Field without SearchFormEntry is invalid - not ok');
}
catch (RuntimeException $e)
{
  $t->pass('Field without SearchFormEntry is invalid - ok');
}

