<?php
/**
 * sfForm for ullVentory
 *
 */
class ullVentoryForm extends ullGeneratorForm
{

  /**
   * Handle item-type and item-model
   * 
   * @see plugins/ullCorePlugin/lib/form/ullGeneratorForm#updateObject()
   */
  public function updateObject($values = null)
  {
    $values = $this->getValues();
    
    parent::updateObject();
    
    $this->updateManufacturerAndModel($values);
    
    $this->updateAttributes($values);
    
    // why is this necessary?
    $this->object->UllEntity = Doctrine::getTable('UllEntity')->findOneById($values['ull_entity_id']);    

    $this->updateMemory($values);

//    var_dump($this->object->exists());
//    var_dump($this->object->toArray());
//    var_dump($values);
//    die('buha');
    
    return $this->object;
  }
  
  
  /**
   * Update manufacturer and model
   * and handle the "create new" input fields 
   * 
   * @param array $values
   * @return none
   */
  protected function updateManufacturerAndModel(array $values)
  {
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
    else
    {
      $model = Doctrine::getTable('UllVentoryItemModel')->findOneById($values['ull_ventory_item_model_id']);
    }

    $model->ull_ventory_item_type_id = $values['ull_ventory_item_type_id'];
    $model->ull_ventory_item_manufacturer_id = $manufacturer->id;
    $model->save();
    
    $this->object->UllVentoryItemModel = $model;   
  }
  
  
  /**
   * Update the item's attributes
   * 
   * @param $values
   * @return none
   */
  protected function updateAttributes($values)
  {
    if (isset($values['attributes']))
    {
      $i = 0;
      
      foreach ($values['attributes'] as $attribute)
      {
//        var_dump($attribute);
        //create
        if (!$this->object->exists())
        {
          $this->object->UllVentoryItemAttributeValue[$i]['ull_ventory_item_type_attribute_id'] = $attribute['ull_ventory_item_type_attribute_id'];
          $this->object->UllVentoryItemAttributeValue[$i]['value'] = $attribute['value'];
          $this->object->UllVentoryItemAttributeValue[$i]['comment'] = $attribute['comment'];
          $i++;
        }
        //update
        else
        {
          $attributeValue = Doctrine::getTable('UllVentoryItemAttributeValue')->findOneByID($attribute['id']);
          $attributeValue->fromArray($attribute);
          $attributeValue->save();
        }
      }
    }
  }
  
  protected function updateMemory($values)
  {
    if (isset($values['memory']))
    {
//      var_dump($values['memory']);die;

      //create
      if (!$this->object->exists())
      {
        $this->object->UllVentoryItemMemory[0]['source_ull_entity_id']  = $values['memory']['target_ull_entity_id'];
        $this->object->UllVentoryItemMemory[0]['target_ull_entity_id']  = $values['memory']['target_ull_entity_id'];
        $this->object->UllVentoryItemMemory[0]['transfer_at']           = $values['memory']['transfer_at'];
        $this->object->UllVentoryItemMemory[0]['comment']               = $values['memory']['comment'];        

        $this->object->UllVentoryItemMemory[1]['source_ull_entity_id']  = $values['memory']['target_ull_entity_id'];
        $this->object->UllVentoryItemMemory[1]['target_ull_entity_id']  = $values['ull_entity_id'];
        $this->object->UllVentoryItemMemory[1]['transfer_at']           = date('Y-m-d');

      }
      //edit
      else
      {
        //create memory only if we have a new memory comment, or the owner changed
        if ($values['memory']['comment'] || $values['memory']['target_ull_entity_id'] != $values['ull_entity_id'])
        {
          $num = count($this->object->UllVentoryItemMemory);
          
          $this->object->UllVentoryItemMemory[$num]['source_ull_entity_id']  = $values['ull_entity_id'];
          $this->object->UllVentoryItemMemory[$num]['target_ull_entity_id']  = $values['memory']['target_ull_entity_id'];
          $this->object->UllVentoryItemMemory[$num]['transfer_at']           = date('Y-m-d');
          $this->object->UllVentoryItemMemory[$num]['comment']               = $values['memory']['comment'];
          
          $this->object->UllEntity = Doctrine::getTable('UllEntity')->findOneById($values['memory']['target_ull_entity_id']);
        }          
      }
      
      
    }
  }

  /**
   * Load the item-type and item-manufacturer for the given item-model
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/form/sfFormDoctrine#updateDefaultsFromObject()
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    $defaults = $this->getDefaults();

    // defaults: set item type and manufacturer because they're not native 
    $model = $this->getObject()->UllVentoryItemModel;
    $defaults['ull_ventory_item_type_id'] = $model->ull_ventory_item_type_id;
    $defaults['ull_ventory_item_manufacturer_id'] = $model->ull_ventory_item_manufacturer_id;
    // for the list view:
    $defaults['ull_location_id'] = $this->getObject()->UllEntity->ull_location_id;
    
    $this->setDefaults($defaults);
  }  
  
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    //do nothing - don't save embeded forms
  }  
}