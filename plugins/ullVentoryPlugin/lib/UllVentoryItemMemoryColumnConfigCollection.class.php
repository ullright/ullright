<?php 

class UllVentoryItemMemoryColumnConfigCollection extends ullColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array(
      'id', 
      'ull_ventory_item_id',
      'source_ull_entity_id',
      'creator_user_id',
      'created_at',
      'updator_user_id',
      'updated_at'      
    ));
    
    $this['target_ull_entity_id']->setMetaWidgetClassName('ullMetaWidgetUllEntity');    
    
    $this['comment']->setMetaWidgetClassName('ullMetaWidgetString');
    
    if ($this->isAction('createWithType'))
    {
      $this['target_ull_entity_id']
        ->setLabel(__('Origin', null, 'common'))
        ->setOption('entity_classes', array('UllVentoryOriginDummyUser'))
      ;
      
      $this['transfer_at']
        ->setMetaWidgetClassName('ullMetaWidgetDate')
        ->setLabel(__('Date', null, 'common'))
        ->setHelp(__('Set the actual delivery date in case of a delivery'))
      ;
      
      $this->order(array('target_ull_entity_id', 'transfer_at', 'comment'));      
    }
    
    if ($this->isEditAction())
    {
      $this['transfer_at']->disable();
      $this['target_ull_entity_id']
        ->setLabel(__('Owner', null, 'common'))
        ->setOption('entity_classes', array('UllVentoryStatusDummyUser', 'UllUser'))
      ;
      
      $this->order(array('target_ull_entity_id', 'comment'));
    }
  }     
  
}