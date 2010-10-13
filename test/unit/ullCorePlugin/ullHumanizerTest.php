<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();
sfContext::getInstance()->getConfiguration()->loadHelpers('ull');
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(8, new lime_output_color);

$t->diag('testing has* functions');

  $t->is(ullHumanizer::hasColumnNameHumanization('sure_to_be_never_humanized_column_name'), false,
    'hasColumnNameHumanization - not existing - returns correct result');
  $t->is(ullHumanizer::hasColumnNameHumanization('ull_company_id'), true,
    'hasColumnNameHumanization - existing - returns correct result');
  
  $t->is(ullHumanizer::hasRelationHumanization('sure_to_be_never_humanized_relation'), false,
    'hasRelationHumanization - not existing - returns correct result');
  $t->is(ullHumanizer::hasRelationHumanization('Creator'), true,
    'hasRelationHumanization - existing - returns correct result');

$t->diag('testing humanize* functions');

  $t->is(ullHumanizer::humanizeAndTranslateColumnName('sure_to_be_never_humanized_column_name'),
    'Sure to be never humanized column name',
    'humanizeAndTranslateColumnName - not existing - returns correct result');
  $t->is(ullHumanizer::humanizeAndTranslateColumnName('ull_company_id'), 'Company',
    'humanizeAndTranslateColumnName - existing - returns correct result');
  
  $t->is(ullHumanizer::humanizeAndTranslateRelation('sure_to_be_never_humanized_relation'),
    'sure_to_be_never_humanized_relation', 'humanizeAndTranslateRelation - not existing - returns correct result');
  $t->is(ullHumanizer::humanizeAndTranslateRelation('Creator'), 'Created by',
    'humanizeAndTranslateRelation - existing - returns correct result');
  
?>