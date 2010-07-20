<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findBookableResources()');
  $resources = Doctrine::getTable('UllBookingResource')->findBookableResources();
  $shouldBe = array(1 => 'Bouldering room', 2 => 'Gallery sector');
  
  foreach ($resources as $resource)
  {
    $t->is($shouldBe[$resource->id], $resource->name, 'returns a correct bookable resource');
    unset($shouldBe[$resource->id]);
  }
  
  $t->is(count($shouldBe), 0, 'returns the correct amount of bookable resources');
