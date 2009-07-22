<?php 

class ullVentoryItemColumnConfigCollection extends ullColumnConfigCollection
{

  public static function build($action = 'edit')
  {
    $c = new self('UllVentoryItem', $action);
    $c->buildCollection();
    
    return $c;
  }
  
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
        ->setMetaWidgetClassName('ullMetaWidgetLink')
        ->setLabel(__('Inv.No.'))
      ;
      
      $this->create('ull_location_id')
        ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
        ->setLabel(__('Location'))
        ->setRelation(array(
          'model'             => 'UllLocation',
          'foreign_id'        => 'id'))
      ;
      
      $this['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate'); //?            
      
      $this->order(array(
        'inventory_number',
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
//        'serial_number',
        'ull_entity_id',
        'ull_location_id',      
        'comment'
      ));      
      
    }
    
    if ($this->isCreateOrEditAction())
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
  //      'ull_entity_id',
  //      'ull_location_id',
        'comment'
      ));      
    }  
    
  }     
  
}