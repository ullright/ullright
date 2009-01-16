<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findIdBySlug()');

  $t->is(UllFlowActionTable::findIdBySlug('create'), 1, 'return the correct id');
  $t->is(UllFlowActionTable::findIdBySlug('foobar'), null, 'return null for an invalid slug');

