<?php
/**
 * sfForm for ullVentory
 *
 */
class ullVentoryForm extends ullGeneratorForm
{   

  /**
   * Handle item-tye and item-model
   * 
   * @see plugins/ullCorePlugin/lib/form/ullGeneratorForm#updateObject()
   */
  public function updateObject($values = null)
  {
    parent::updateObject();
    
    $values = $this->getValues();
    
    if ($manufacturerName = $values['ull_ventory_item_manufacturer_id_create'])
    {
      $manufacturer = Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName($manufacturerName);
      
      if (!$manufacturer)
      {
        $manufacturer = new UllVentoryItemManufacturer;
        $manufacturer->name = $manufacturerName;
        $manufacturer->save();        
      }
    }
    else
    {
      $manufacturer = Doctrine::getTable('UllVentoryItemManufacturer')->findOneById($values['ull_ventory_item_manufacturer_id']);
    }
    
    if ($modelName = $values['ull_ventory_item_model_id_create'])
    {
      $model = Doctrine::getTable('UllVentoryItemModel')->findOneByName($modelName);
      
      if (!$model)
      {
        $model = new UllVentoryItemModel;
        $model->name = $modelName;
      }
    }

    $model->ull_ventory_item_type_id = $values['ull_ventory_item_type_id'];
    $model->ull_ventory_item_manufacturer_id = $manufacturer->id;
    $model->save();
    
    $this->object->ull_ventory_item_model_id = $model->id;
    
    return $this->object;
  }

  /**
   * Load the item-type and item-manufacturer for the given item-model
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/form/sfFormDoctrine#updateDefaultsFromObject()
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    if (!$this->isNew()) 
    {
      $defaults = $this->getDefaults();
      
      $model = Doctrine::getTable('UllVentoryItemModel')->findOneById($defaults['ull_ventory_item_model_id']);
      $defaults['ull_ventory_item_type_id'] = $model->ull_ventory_item_type_id;
      $defaults['ull_ventory_item_manufacturer_id'] = $model->ull_ventory_item_manufacturer_id;
      
      $this->setDefaults($defaults);
    }
  }  
  
}