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

$t->begin('findByItemIdAndTypeAttributeId()');

  $item = Doctrine::getTable('UllVentoryItem')->findOneByInventoryNumber('1701');
  $q = new Doctrine_Query;
  $q
    ->from('UllVentoryItemTypeAttribute x')
    ->where('x.UllVentoryItemType.slug = ?', 'notebook')
    ->addWhere('x.UllVentoryItemAttribute.slug = ?', 'display-size')
  ;
  $typeAttribute = $q->execute()->getFirst();
  
  $t->is(UllVentoryItemAttributeValueTable::findByItemIdAndTypeAttributeId($item->id, $typeAttribute->id)->value, '13', 'returns the correct attribute value');
  
