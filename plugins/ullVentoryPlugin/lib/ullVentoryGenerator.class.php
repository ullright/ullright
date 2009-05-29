<?php

class ullVentoryGenerator extends ullTableToolGenerator
{
  protected
    $formClass = 'ullVentoryForm',
    
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
    $this->modelName = 'UllVentoryItem';
    
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
      $this->columnsConfig['creator_user_id'],
      $this->columnsConfig['created_at']      
    );
    
    $this->columnsConfig['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');
    
    if ($this->requestAction == 'edit')
    {
      unset(
//        $this->columnsConfig['id'],        
        $this->columnsConfig['updator_user_id'],
        $this->columnsConfig['updated_at']
      );
      $this->columnsConfig['id']->setAccess('w');
      $this->columnsConfig['id']->setWidgetOption('is_hidden', true);
//      $this->columnsConfig['id']['metaWidget'] = 'ullMetaWidgetHidden';
    }
    else
    {
      unset(
        $this->columnsConfig['id']        
      );      
    }
    
    $itemTypeCC = new ullColumnConfiguration();
    $itemTypeCC->setMetaWidgetClassName('ullMetaWidgetForeignKey');
    $itemTypeCC->setLabel('Type');
	  $itemTypeCC->setRelation(array(
      'model'             => 'UllVentoryItemType',
      'foreign_id'        => 'id'));
    $itemTypeCC->setWidgetOptions(array('add_empty' => true));
    $itemTypeCC->setValidatorOptions(array('required' => true));
    $itemTypeCC->setIsInList(false);
    
    $this->columnsConfig['ull_ventory_item_type_id'] = $itemTypeCC;
    
    $itemManufactorCC = new ullColumnConfiguration();
    $itemManufactorCC->setMetaWidgetClassName('ullMetaWidgetForeignKey');
    $itemManufactorCC->setLabel(__('Manufacturer'));
    $itemManufactorCC->setRelation(array(
        'model'             => 'UllVentoryItemManufacturer',
        'foreign_id'        => 'id'));
    $itemManufactorCC->setWidgetOptions(array('add_empty' => true));
    $itemManufactorCC->setValidatorOptions(array('required' => true));
    $itemManufactorCC->setAllowCreate(true);
    $itemManufactorCC->setIsInList(false);
    
    $this->columnsConfig['ull_ventory_item_manufacturer_id'] = $itemManufactorCC;
        
    $this->columnsConfig['ull_ventory_item_model_id']->setAllowCreate(true);
    $this->columnsConfig['ull_ventory_item_model_id']->setWidgetOption('add_empty', true);
    
    $this->columnsConfig['ull_user_id']->setLabel(__('Owner', null, 'common'));
//    $this->columnsConfig['ull_location_id']['label'] = __('Item location');
    $this->columnsConfig['ull_ventory_item_model_id']->setLabel(__('Model'));
    if ($this->requestAction == 'edit')
    {
      $this->columnsConfig['inventory_number']->setLabel(__('Inventory number'));
    }
    else
    {
      $this->columnsConfig['inventory_number']->setLabel(__('Inv.No.'));
    }
    $this->columnsConfig['serial_number']->setLabel(__('Serial number'));
    $this->columnsConfig['comment']->setLabel(__('Comment', null, 'common'));
    
    
    $order = array(      
      'ull_ventory_item_type_id',
      'ull_ventory_item_manufacturer_id',
      'ull_ventory_item_model_id',
      'inventory_number',
      'serial_number',
      'ull_user_id',
//      'ull_location_id',
      'comment'
    );
  
    $this->columnsConfig = ull_order_array_by_array($this->columnsConfig, $order);
    
//    var_dump($this->columnsConfig);die;
  }

  
  /**
   * Extends parents buildForm method
   * 
   * @see plugins/ullCorePlugin/lib/ullGenerator#buildForm()
   */
  public function buildForm($rows)
  {
    parent::buildForm($rows);
    
    $this->filterItemModelsByManufacturer();
    
//    if ($this->getRequestAction() == 'edit')
//    { 
//         
//      $this->getForm()->mergePostValidator(
//        new sfValidatorDoctrineUnique(array('model' => 'UllVentoryItem', 'column' => 'id'))
//      );
//      
//      
////      var_dump($this->getForm()->getValidatorSchema()->getPostValidator()->get->[1]);die;      
//    }
  }  
  
  
  /**
   * Adds filter to the item-model select box by the given manufacturer
   * Actually adds an option (custom query) for the sfWidgetFormDoctrineSelect widget
   *  
   * @return none
   */
  protected function filterItemModelsByManufacturer()
  {
    if ($this->getRequestAction() == 'edit' && $this->getRow()->exists())
    {
      $q = new Doctrine_Query;
      $q
        ->from('UllVentoryItemModel mo')
        ->where('mo.ull_ventory_item_manufacturer_id = ?', $this->getForm()->getDefault('ull_ventory_item_manufacturer_id'))
      ;
      
      $this->getForm()->getWidgetSchema()->offsetGet('ull_ventory_item_model_id')->addOption('alias', 'mo');
      $this->getForm()->getWidgetSchema()->offsetGet('ull_ventory_item_model_id')->addOption('query', $q);
    }    
  }
}