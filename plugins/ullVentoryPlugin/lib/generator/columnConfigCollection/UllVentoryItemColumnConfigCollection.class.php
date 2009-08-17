<?php 

class UllVentoryItemColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('creator_user_id', 'created_at'));
    
    $this->create('ull_ventory_item_type_id')
      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ->setLabel(__('Type', null, 'common'))
      ->setRelation(array(
        'model'             => 'UllVentoryItemType',
        'foreign_id'        => 'id'))
      ->setWidgetOptions(array('add_empty' => true))
      ->setValidatorOptions(array('required' => true))
    ;

    $this->create('ull_ventory_item_manufacturer_id')
      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ->setLabel(__('Manufacturer', null, 'ullVentoryMessages'))
      ->setRelation(array(
        'model'             => 'UllVentoryItemManufacturer',
        'foreign_id'        => 'id'))
      ->setWidgetOptions(array('add_empty' => true))
      ->setValidatorOptions(array('required' => true))
      ->setAllowCreate(true)
    ;    
        
    $this['ull_ventory_item_model_id']
      ->setLabel(__('Model', null, 'ullVentoryMessages'))
      ->setAllowCreate(true)
      ->setWidgetOption('add_empty', true)
    ;
    
    $this['serial_number']
      ->setLabel(__('Model', null, 'ullVentoryMessages'))
      ->setIsInList(false)
      ->setLabel(__('Serial number', null, 'ullVentoryMessages'))
    ;    
    
    $this['ull_entity_id']
      ->setLabel(__('Owner',  null, 'common'))
      ->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'))
    ;

    $this['comment']
      ->setLabel(__('Comment', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetString')
    ;
    $this['inventory_number']->setLabel(__('Inventory number', null, 'ullVentoryMessages'));
    
    if ($this->isListAction())
    {
      $this->disable(array('id', 'comment'));
      
      $this['inventory_number']
        //->setMetaWidgetClassName('ullMetaWidgetLink') disabled, because a link should point to show, not edit
        ->setLabel(__('Inv.No.', null, 'ullVentoryMessages'))
      ;
      
      $this->create('toggle_inventory_taking')
        ->setMetaWidgetClassName('ullMetaWidgetUllVentoryTaking')
        ->setLabel(' ')
      ;
      
      $this->create('ull_location_id')
        ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
        ->setLabel(__('Location', null, 'ullVentoryMessages'))
        ->setRelation(array(
          'model'             => 'UllLocation',
          'foreign_id'        => 'id'))
      ;
      
      // Display only date - no time
      $this['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');

      // Don't display owner column if only items of one owner are shown
      if (sfContext::getInstance()->getRequest()->hasParameter('filter[ull_entity_id]'))
      {
        $this['ull_entity_id']->disable();
      }
      
      $this->order(array(
        'inventory_number',
        'toggle_inventory_taking',
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
        'ull_entity_id',
        'ull_location_id',      
      ));      
      
    }
    
    if ($this->isAction('createWithType'))
    {
      $this->disable(array('updator_user_id', 'updated_at'));
      
      // Attributes: save as preset
      $this->create('save_preset')
        ->setMetaWidgetClassName('ullMetaWidgetCheckbox')
        ->setLabel(__('Save as preset', null, 'ullVentoryMessages'))
        ->setHelp(__('Save attributes as preset for the current model', null, 'ullVentoryMessages'))
      ;      
    }
    
    if ($this->isAction(array('createWithType', 'edit')))
    {
      $this['id']
        ->setAccess('w')
        ->setWidgetOption('is_hidden', true)
      ;
      $this['ull_entity_id']->setWidgetOption('is_hidden', true);
      
      $this['inventory_number']->setLabel(__('Inventory number', null, 'ullVentoryMessages'));
      
      $this->order(array(
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
        'inventory_number',
        'serial_number',
        'comment'
      ));  

      // Software: add software
      $this->create('add_software')
        ->setMetaWidgetClassName('ullMetaWidgetUllVentorySoftware')
        ->setLabel(__('Add software', null, 'ullVentoryMessages'))
      ;       
    }  
    
  }
}