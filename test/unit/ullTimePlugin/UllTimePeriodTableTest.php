<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('findSlugByDate()');
  $t->is(UllTimePeriodTable::findSlugByDate('2009-09-22'), 'september-2009', 'returns the correct period slug');
  
  
$t->diag('findCurrentAndPast()');
  $periods = UllTimePeriodTable::findCurrentAndPast('2009-09-23');
  $t->is(count($periods), 2, 'Returns the correct number of periods');  
  $t->is($periods->getFirst()->slug, 'september-2009', 'Returns the correct period');   


$t->diag('periodExists()');
  $t->is(UllTimePeriodTable::periodExists('2009-11-01', '2009-11-30'), false, 'Returns false');  
  $t->is(count(UllTimePeriodTable::periodExists('2009-09-21', '2009-10-20')), 2, 'Returns two overlapping existing periods');
