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
    
    unset(      
      $this->columnsConfig['creator_user_id'],
      $this->columnsConfig['created_at']      
    );
    
    $this->columnsConfig['updated_at']['metaWidget']  = 'ullMetaWidgetDate';
    
    if ($this->requestAction == 'edit')
    {
      unset(
        $this->columnsConfig['id'],        
        $this->columnsConfig['updator_user_id'],
        $this->columnsConfig['updated_at']
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
    
  $order = array(
    'ull_ventory_item_type_id',
    'ull_ventory_item_manufacturer_id',
    'ull_ventory_item_model_id'
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