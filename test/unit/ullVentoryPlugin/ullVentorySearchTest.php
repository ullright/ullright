<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

/*
 * Note: This test only checks if the query modifications done
 * by the ullVentorySearch class are correct, it does not retrieve
 * the actual results. The functional tests cover this aspect.
 * This test only checks for additions from the ventory search class,
 * also see ullSearchTest.php
 */

$t->begin('ullVentorySearchTest - criterion classes and groups');

$query = new Doctrine_Query();
$query
  ->from('UllVentoryItem x')
;
$originalSql = $query->getSql();

$search = new ullVentorySearch();
$search->modifyQuery($query, 'x');

$t->is($originalSql, $query->getSql(), 'empty UllVentoryItem does not modify query - ok');

//double range criterion with NOT
$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'isVirtual.display-size';
$criterion->fromValue = 13;
//we don't set TO here on purpose

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;

$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'isVirtual.wired-network-speed';
//we don't set FROM here on purpose
$criterion->toValue = 1000;

$criterionGroup->subCriteria[] = $criterion;
$criterionGroups = array($criterionGroup);

$search->addCriterionGroups($criterionGroups);
$search->modifyQuery($query, 'x');

$displaySizeId = Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug('display-size')->id;
$wiredNetworkSpeedId = Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug('wired-network-speed')->id;
$paramArray = $query->getParams();

$t->like($query->getSql(), '/LEFT JOIN ull_ventory_item_attribute_value u2 ON u.id = u2.ull_ventory_item_id '
. 'AND u2.ull_ventory_item_type_attribute_id IN '
. '\(SELECT u4.id AS u4__id FROM ull_ventory_item_type_attribute u4 WHERE u4.ull_ventory_item_attribute_id = \?\) '
. 'LEFT JOIN ull_ventory_item_attribute_value u3 ON u.id = u3.ull_ventory_item_id AND '
. 'u3.ull_ventory_item_type_attribute_id IN '
. '\(SELECT u5.id AS u5__id FROM ull_ventory_item_type_attribute u5 WHERE u5.ull_ventory_item_attribute_id = \?\) '
. 'WHERE \(u2.value >= \? OR u3.value <= \?\)/', 'double range criterion with item attributes - SQL is correct');

$t->is($displaySizeId, $paramArray[0], 'column id correct');
$t->is($wiredNetworkSpeedId, $paramArray[1], 'column id correct');
$t->is(13, $paramArray[2], 'search range param 1 correct');
$t->is(1000, $paramArray[3], 'search range param 2 correct');
