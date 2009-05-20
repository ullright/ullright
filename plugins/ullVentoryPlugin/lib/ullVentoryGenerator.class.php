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
    
    $this->columnsConfig['updated_at']['metaWidget']  = 'ullMetaWidgetDate';
    
    if ($this->requestAction == 'edit')
    {
      unset(
//        $this->columnsConfig['id'],        
        $this->columnsConfig['updator_user_id'],
        $this->columnsConfig['updated_at']
      );
      $this->columnsConfig['id']['access'] = 'w';
      $this->columnsConfig['id']['widgetOptions']['is_hidden'] = true;
      $this->columnsConfig['id']['widgetOptions']['is_hidden'] = true;
//      $this->columnsConfig['id']['metaWidget'] = 'ullMetaWidgetHidden';
    }
    else
    {
      unset(
        $this->columnsConfig['id']        
      );      
    }
    
    $itemType = array(
      'metaWidget'        => 'ullMetaWidgetForeignKey',
      'label'             => 'Type',
      'access'            => 'w',
      'is_in_list'        => false,
      'relation'          => array(
        'model'             => 'UllVentoryItemType',
        'foreign_id'        => 'id'
        ),
      'widgetOptions'     => array('add_empty' => true), 
      'validatorOptions'  => array('required' => true),
      'widgetAttributes'  => array()
    );
    $this->columnsConfig['ull_ventory_item_type_id'] = $itemType;
    
    $itemManufacturer = array(
      'metaWidget'        => 'ullMetaWidgetForeignKey',
      'label'             => __('Manufacturer'),
      'access'            => 'w',
      'is_in_list'        => false,
      'relation'          => array(
        'model'             => 'UllVentoryItemManufacturer',
        'foreign_id'        => 'id'
        ),
      'widgetOptions'     => array('add_empty' => true), 
      'validatorOptions'  => array('required' => true),
      'widgetAttributes'  => array(),
      'allowCreate'       => true
    );
    $this->columnsConfig['ull_ventory_item_manufacturer_id'] = $itemManufacturer;
        
    $this->columnsConfig['ull_ventory_item_model_id']['allowCreate']  = true;
    $this->columnsConfig['ull_ventory_item_model_id']['widgetOptions']['add_empty']  = true;
    
    $this->columnsConfig['ull_user_id']['label'] = __('Owner', null, 'common');
//    $this->columnsConfig['ull_location_id']['label'] = __('Item location');
    $this->columnsConfig['ull_ventory_item_model_id']['label'] = __('Model');
    if ($this->requestAction == 'edit')
    {
      $this->columnsConfig['inventory_number']['label'] = __('Inventory number');
    }
    else
    {
      $this->columnsConfig['inventory_number']['label'] = __('Inv.No.');
    }
    $this->columnsConfig['serial_number']['label'] = __('Serial number');
    $this->columnsConfig['comment']['label'] = __('Comment', null, 'common');
    
    
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