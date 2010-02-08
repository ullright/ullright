<?php
/**
 * sfForm for ullVentory
 *
 */
class ullVentoryGeneratorForm extends ullGeneratorForm
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
    
    $this->updateSoftware($values);
    
    // why is this necessary?
    $this->object->UllEntity = Doctrine::getTable('UllEntity')->findOneById($values['ull_entity_id']);    

    $this->updateMemory($values);
    
    $this->savePreset($values);

//    var_dump($this->object->exists());
//    var_dump($values);
//    var_dump($this->object->toArray());
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
//    var_dump($values);die;
    
    if (isset($values['attributes']))
    {
      foreach ($values['attributes'] as $attribute)
      {
        if ($attribute['value'])
        {
          // convert e.g. booleans to string, since the database column is string
          $attribute['value'] = (string) $attribute['value'];
          
          //create
          if (!$attribute['id'])
          {
            $attributeValue = new UllVentoryItemAttributeValue;
            $attributeValue->fromArray($attribute);
            $this->object->UllVentoryItemAttributeValue[] = $attributeValue;
          }
          //update
          else
          {
            foreach ($this->object->UllVentoryItemAttributeValue as $key => $attributeValue)
            {
              // a changed value is ignored when we assign a new attributeValue object
              if ($attributeValue->id == $attribute['id'])
              {
                $attributeValue = $this->object->UllVentoryItemAttributeValue[$key];
                $attributeValue->fromArray($attribute);
              }
            }
          }            
        }
        else
        {
          if ($attribute['id'])
          {
            // we properly remove the attributeValue from the item object graph
            foreach ($this->object->UllVentoryItemAttributeValue as $key => $attributeValue)
            {
              if ($attributeValue->id == $attribute['id'])
              {
                unset($this->object->UllVentoryItemAttributeValue[$key]);
              }  
            }
          }
        }
      }
    }
  }

  /**
   * Updates the software of an item.
   * 
   * @param $values
   * @return unknown_type
   */
  protected function updateSoftware($values)
  {
    if (isset($values['software']))
    {
      
      foreach($values['software'] as $software)
      {
        if ($software['enabled'])
        {
          if ($software['id'])
          {
            $itemSoftware = Doctrine::getTable('UllVentoryItemSoftware')->findOneById($software['id']);
            $itemSoftware->fromArray($software);
            $itemSoftware->save();
          }
          else
          {
            $itemSoftware = new UllVentoryItemSoftware;
            $itemSoftware->fromArray($software);
            $this->object->UllVentoryItemSoftware[] = $itemSoftware;
          }
          
        }
        else
        {
          if ($software['id'])
          {
            foreach ($this->object->UllVentoryItemSoftware as $key => $itemSoftware)
            {
              if ($itemSoftware->id == $software['id'])
              {
                unset($this->object->UllVentoryItemSoftware[$key]);
              }  
            }
          }  
        }
      }
      
      if (isset($values['add_software']))
      {
        $exists = false;
        foreach($this->object->UllVentoryItemSoftware as $itemSoftware)
        {
          if ($itemSoftware->ull_ventory_software_id == $values['add_software'])
          {
            $exists = true;
            break;
          }
        }
        
        if (!$exists)
        {
          $itemSoftware = new UllVentoryItemSoftware;
          $itemSoftware->ull_ventory_software_id = $values['add_software'];
          $this->object->UllVentoryItemSoftware[] = $itemSoftware;
        }
      }  
    
    }
  }
  
  /**
   * Update the item's memories
   * 
   * @param $values
   * @return none
   */
  protected function updateMemory($values)
  {
    if (isset($values['memory']))
    {
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
          $memory = new UllVentoryItemMemory();
          $memory->source_ull_entity_id = $values['ull_entity_id'];
          $memory->target_ull_entity_id = $values['memory']['target_ull_entity_id'];
          $memory->transfer_at = date('Y-m-d');
          $memory->comment = $values['memory']['comment'];
          $this->object->UllVentoryItemMemory[] = $memory;
          
          $this->object->UllEntity = Doctrine::getTable('UllEntity')->findOneById($values['memory']['target_ull_entity_id']);
        }          
      }
    }
  }
  
  /**
   * Save presets 
   * 
   * @param $values
   * @return none
   */
  protected function savePreset($values)
  {
    if (isset($values['save_preset']) && $values['save_preset'])
    {
      foreach($values['attributes'] as $attribute)
      {
        $typeAttribute = Doctrine::getTable('UllVentoryItemTypeAttribute')->findOneById($attribute['ull_ventory_item_type_attribute_id']);
        
        if ($attribute['value'] && $typeAttribute->is_presetable)
        {
          UllVentoryItemAttributePresetTable::saveValueByModelIdAndTypeAttributeId(
            $attribute['value'],
            $this->object->ull_ventory_item_model_id,
            $typeAttribute->id
          );
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
    //parent updateDefaultsFromObject()
    
    if ($this->isNew())
    {
      $this->setDefaults(array_merge($this->getObject()->toArray(false), $this->getDefaults()));
    }
    else
    {
      //$this->setDefaults(array_merge($this->getDefaults(), $this->getObject()->toArray(false)));
      $this->setDefaults(array_merge($this->getDefaults(), $this->getObject()->getData()));
    }
    
    $defaults = $this->getDefaults();
    foreach ($this->embeddedForms as $name => $form)
    {
      if ($form instanceof sfFormDoctrine)
      {
        $form->updateDefaultsFromObject();
        $defaults[$name] = $form->getDefaults();
      }
    }
    $this->setDefaults($defaults);
    
    //end parent updateDefaultsFromObject()
    
    $defaults = $this->getDefaults();

    // defaults: set item type and manufacturer because they're not native 
    $model = $this->getObject()->UllVentoryItemModel;
    $defaults['ull_ventory_item_type_id'] = $model->ull_ventory_item_type_id;
    $defaults['ull_ventory_item_manufacturer_id'] = $model->ull_ventory_item_manufacturer_id;
    // for the list view:
    $defaults['ull_location_id'] = $this->getObject()->UllEntity->ull_location_id;
    $defaults['artificial_toggle_inventory_taking'] = $this->getObject()->hasLatestInventoryTaking();
    
//    var_dump($defaults);die('ullVentoryForm::updateDefaultsFromObject');
    
    $this->setDefaults($defaults);
  }  
  
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    //do nothing - don't save embeded forms
  } 

  public function updateObjectEmbeddedForms($values, $forms = null)  
  {
    //do nothing - don't save embeded forms
  }  
}