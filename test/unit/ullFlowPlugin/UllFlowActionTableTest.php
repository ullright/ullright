<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findIdBySlug()');

  $t->is(UllFlowActionTable::findIdBySlug('create'), 1, 'return the correct id');
  $t->is(UllFlowActionTable::findIdBySlug('foobar'), null, 'return null for an invalid slug');

$t->diag('isNotStatusOnly()');

  $t->is(UllFlowActionTable::isNonStatusOnly('save_close'), false, 'returns false for a status only action slug');
  $t->is(UllFlowActionTable::isNonStatusOnly('send'), true, 'returns false for a non-status only action slug');
  $t->is(UllFlowActionTable::isNonStatusOnly('foobar'), false, 'returns false for an invalid action slug');
  $t->is(UllFlowActionTable::isNonStatusOnly(null), false, 'returns false for null');