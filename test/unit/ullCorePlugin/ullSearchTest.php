<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
sfLoader::loadHelpers('ull');

$t = new lime_test(9, new lime_output_color);

/*
 * Note: This test only checks if the query modifications done
 * by the ullSearch class are correct, it does not retrieve
 * the actual results. The functional tests cover this aspect.
 */

$t->diag('ullSearchTest - criterion classes and groups');

$query = new Doctrine_Query();
$query
  ->from('UllUser u')
;
$originalSql = $query->getSqlQuery();

$search = new ullSearch();
$search->modifyQuery($query, 'u');

$t->is($originalSql, $query->getSqlQuery(), 'empty ullSearch does not modify query - ok');

//simple criterion
$criterion = new ullSearchCompareExactCriterion();
$criterion->columnName = 'display_name';

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;
$criterionGroups = array();
$criterionGroups[] = $criterionGroup;

//compare criterion
$criterion = new ullSearchCompareCriterion();
$criterion->columnName = 'first_name';
$criterion->compareValue = array('bob', 'alice');

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;
$criterionGroups[] = $criterionGroup;

//simple criterion with NOT
$criterion = new ullSearchCompareExactCriterion();
$criterion->columnName = 'last_name';
$criterion->isNot = true;

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;
$criterionGroups[] = $criterionGroup;

//range criterion
$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'phone_extension';
$criterion->fromValue = 1000;
$criterion->toValue = 2000;

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;
$criterionGroups[] = $criterionGroup;

//double range criterion with NOT
$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'fax_extension';
$criterion->fromValue = 1000;
//we don't set TO here on purpose
$criterion->isNot = true;

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;

$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'fax_extension';
//we don't set FROM here on purpose
$criterion->toValue = 3000;
$criterion->isNot = true;

$criterionGroup->subCriteria[] = $criterion;
$criterionGroups[] = $criterionGroup;

//boolean criterion
$criterion = new ullSearchBooleanCriterion();
$criterion->columnName = 'is_show_extension_in_phonebook';
$criterion->compareValue = true;

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;
$criterionGroups[] = $criterionGroup;

//foreign criterion
$criterion = new ullSearchForeignKeyCriterion();
$criterion->columnName = 'ull_location_id';

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;
$criterionGroups[] = $criterionGroup;

$search->addCriterionGroups($criterionGroups);
$search->modifyQuery($query, 'u');

$t->like($query->getSqlQuery(), '/WHERE \(u.display_name LIKE \?/', 'simple criterion - SQL is correct');
$t->like($query->getSqlQuery(), '/\(u.first_name LIKE \? AND u.first_name LIKE \?\)/', 'compare criterion - SQL is correct');
$t->like($query->getSqlQuery(), '/\(NOT \(u.last_name LIKE \?\)\)/', 'simple criterion with NOT - SQL is correct');
$t->like($query->getSqlQuery(), '/u.phone_extension BETWEEN \? AND \?/', 'range criterion - SQL is correct');
$t->like($query->getSqlQuery(), '/\(\(NOT \(u.fax_extension >= \?\)\) OR \(NOT \(u.fax_extension <= \?\)\)\)/', 'double range criterion with NOT - SQL is correct');
$t->like($query->getSqlQuery(), '/u.is_show_extension_in_phonebook IS TRUE/', 'boolean criterion with NOT - SQL is correct');
$t->like($query->getSqlQuery(), '/u.ull_location_id = ?/', 'foreign criterion with NOT - SQL is correct');


$t->diag('ullSearchTest - query class exception');

$search = new ullSearch();
$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = 'stone';
$search->addCriterionGroups(array($criterionGroup));

try {
  $search->modifyQuery($query, 'u');
  $t->fail('Unsupported query class exception - NOT ok');
}
catch (RuntimeException $re)
{
  $t->pass('Unsupported query class exception - ok');
}
