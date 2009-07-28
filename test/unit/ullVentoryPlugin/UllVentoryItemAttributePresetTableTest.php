<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findByModelIdAndTypeAttributeId()');

  $model = Doctrine::getTable('UllVentoryItemModel')->findOneByName('Macbook');
  $attribute = Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug('display_size');
  $typeAttribute = $attribute->UllVentoryItemTypeAttribute->getFirst();
  
  $t->is(UllVentoryItemAttributePresetTable::findValueByModelIdAndTypeAttributeId($model->id, $typeAttribute->id), 13, 'Returns the correct value');
  $t->is(UllVentoryItemAttributePresetTable::findValueByModelIdAndTypeAttributeId($model->id, 711), '', 'Returns null when no attributeType found');
  
$t->info('saveValueByModelIdAndTypeAttributeId()');

  $model = Doctrine::getTable('UllVentoryItemModel')->findOneByName('MFC-440CN');
  $attribute = Doctrine::getTable('UllVentoryItemAttribute')->findOneBySlug('printer_type');
  $typeAttribute = $attribute->UllVentoryItemTypeAttribute->getFirst();

  UllVentoryItemAttributePresetTable::saveValueByModelIdAndTypeAttributeId('Inkjet', $model->id, $typeAttribute->id);
  $t->is(UllVentoryItemAttributePresetTable::findValueByModelIdAndTypeAttributeId($model->id, $typeAttribute->id), 'Inkjet', 'Saves the correct value');
  
