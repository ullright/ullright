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
      ->setLabel('Type')
      ->setRelation(array(
        'model'             => 'UllVentoryItemType',
        'foreign_id'        => 'id'))
      ->setWidgetOptions(array('add_empty' => true))
      ->setValidatorOptions(array('required' => true))
    ;
    
    $this->create('ull_ventory_item_manufacturer_id')
      ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ->setLabel(__('Manufacturer'))
      ->setRelation(array(
        'model'             => 'UllVentoryItemManufacturer',
        'foreign_id'        => 'id'))
      ->setWidgetOptions(array('add_empty' => true))
      ->setValidatorOptions(array('required' => true))
      ->setAllowCreate(true)
    ;    
        
    $this['ull_ventory_item_model_id']
      ->setLabel(__('Model'))
      ->setAllowCreate(true)
      ->setWidgetOption('add_empty', true)
    ;
    
    $this['serial_number']
      ->setLabel(__('Model'))
      ->setIsInList(false)
      ->setLabel(__('Serial number'))
    ;    
    
    $this['ull_entity_id']->setLabel(__('Owner', null, 'common'));

    $this['comment']->setLabel(__('Comment', null, 'common'));
    
    if ($this->isListAction())
    {
      $this->disable(array('id'));
      
      $this['inventory_number']
        //->setMetaWidgetClassName('ullMetaWidgetLink') disabled, because a link should point to show, not edit
        ->setLabel(__('Inv.No.'))
      ;
      
      $this->create('toggle_inventory_taking')
        ->setMetaWidgetClassName('ullMetaWidgetUllVentoryTaking')
        ->setLabel(' ')
      ;
      
      $this->create('ull_location_id')
        ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
        ->setLabel(__('Location'))
        ->setRelation(array(
          'model'             => 'UllLocation',
          'foreign_id'        => 'id'))
      ;
      
      // Display only date - no time
      $this['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');             
      
      $this->order(array(
        'inventory_number',
        'toggle_inventory_taking',
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
        'ull_entity_id',
        'ull_location_id',      
        'comment'
      ));      
      
    }
    
    if ($this->isAction('createWithType'))
    {
      $this->disable(array('updator_user_id', 'updated_at'));
      
      $this->create('save_preset')
        ->setMetaWidgetClassName('ullMetaWidgetCheckbox')
        ->setLabel(__('Save as preset'))
        ->setHelp(__('Save attributes as preset for the current model'))
      ;      
    }
    
    if ($this->isAction(array('createWithType', 'edit')))
    {
      $this['id']
        ->setAccess('w')
        ->setWidgetOption('is_hidden', true)
      ;
      $this['ull_entity_id']
        ->setWidgetOption('is_hidden', true)
        ->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'))
      ;
      $this['inventory_number']->setLabel(__('Inventory number'));
      
      $this->order(array(
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
        'inventory_number',
        'serial_number',
        'comment'
      ));      
    }  
    
  }   
}