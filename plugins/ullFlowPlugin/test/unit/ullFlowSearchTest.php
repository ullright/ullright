<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

/*
 * Note: This test only checks if the query modifications done
 * by the ullFlowSearch class are correct, it does not retrieve
 * the actual results. The functional tests cover this aspect.
 * This test only checks for additions from the flow search class,
 * also see ullSearchTest.php
 */

$t->begin('ullFlowSearchTest - criterion classes and groups');

$query = new Doctrine_Query();
$query
  ->from('UllFlowDoc x')
;
$originalSql = $query->getSqlQuery();

$flowApp = Doctrine::getTable('UllFlowApp')->find(1);

$search = new ullFlowSearch($flowApp);
$search->modifyQuery($query, 'x');

$t->is($originalSql, $query->getSqlQuery(), 'empty ullFlowSearch does not modify query - ok');

//double range criterion with NOT
$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'isVirtual.my_priority';
$criterion->fromValue = 2;
//we don't set TO here on purpose

$criterionGroup = new ullSearchCritierionGroup();
$criterionGroup->subCriteria[] = $criterion;

$criterion = new ullSearchRangeCriterion();
$criterion->columnName = 'isVirtual.my_priority';
//we don't set FROM here on purpose
$criterion->toValue = 3;

$criterionGroup->subCriteria[] = $criterion;
$criterionGroups = array($criterionGroup);

$search->addCriterionGroups($criterionGroups);
$search->modifyQuery($query, 'x');

$expectedColumnConfigId = $flowApp->findColumnConfigBySlug('my_priority')->id;
$paramArray = $query->getParams();

$queryParamArray = $query->getParams();
$joinParamArray = $queryParamArray['join'];
$whereParamArray = $queryParamArray['where'];

$t->like($query->getSqlQuery(), '/LEFT JOIN ull_flow_value u2 ON u.id = u2.ull_flow_doc_id AND \(u2.ull_flow_column_config_id = \?\) ' .
'LEFT JOIN ull_flow_value u3 ON u.id = u3.ull_flow_doc_id AND \(u3.ull_flow_column_config_id = \?\) ' .
'WHERE \(u2.value >= \? OR u3.value <= \?\)/', 'double range criterion with virtual columns - SQL is correct');

$t->is($expectedColumnConfigId, $joinParamArray[0], 'column id correct');
$t->is($expectedColumnConfigId, $joinParamArray[1], 'column id correct');
$t->is(2, $whereParamArray[0], 'search range param 1 correct');
$t->is(3, $whereParamArray[1], 'search range param 2 correct');
