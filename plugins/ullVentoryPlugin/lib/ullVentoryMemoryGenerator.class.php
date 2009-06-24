<?php

class ullVentoryMemoryGenerator extends ullTableToolGenerator
{
  protected
    $columnsNotShownInList = array(
    )
  ;
    
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = 'r')
  {
    $this->modelName = 'UllVentoryItemMemory';
    
    parent::__construct($this->modelName, $defaultAccess);
  }  
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    parent::buildColumnsConfig();
    
//    var_dump($this->columnsConfig);die;
    
    unset(
      $this->columnsConfig['id'],
      $this->columnsConfig['ull_ventory_item_id'],      
      $this->columnsConfig['source_ull_entity_id'],
      $this->columnsConfig['creator_user_id'],
      $this->columnsConfig['created_at'],
      $this->columnsConfig['updator_user_id'],
      $this->columnsConfig['updated_at']      
    );
    
    $this->columnsConfig['transfer_at']->setMetaWidgetClassName('ullMetaWidgetDate');
    $this->columnsConfig['transfer_at']->setLabel(__('Date', null, 'common'));
//    $this->columnsConfig['target_ull_entity_id']->setHelp(__('Useful to set the real delivery date', null, 'common'));
//    $this->columnsConfig['target_ull_entity_id']->setMetaWidgetClassName('ullMetaWidgetUllEntity');
    $this->columnsConfig['target_ull_entity_id']->setWidgetOption('model', 'UllVentoryOriginDummyUser');
    $this->columnsConfig['target_ull_entity_id']->setLabel(__('Origin', null, 'common'));
    
    $this->columnsConfig['comment']->setMetaWidgetClassName('ullMetaWidgetString');
    //$this->columnsConfig['comment']->setWidgetAttribute('size', 24);    

    $order = array('target_ull_entity_id', 'transfer_at', 'comment');
    
    $this->columnsConfig = ull_order_array_by_array($this->columnsConfig, $order);    
    
//    var_dump($this->columnsConfig);die;
  }

}