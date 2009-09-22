<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(1, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('findSlugByDate()');
  $t->is(UllTimePeriodTable::findSlugByDate('2009-09-22'), 'september-2009', 'returns the correct period slug');
  
  
