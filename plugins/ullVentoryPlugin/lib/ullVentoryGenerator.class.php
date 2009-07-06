<?php

class ullVentoryGenerator extends ullTableToolGenerator
{
  protected
    $formClass = 'ullVentoryForm',
    $itemType, 
    
    $columnsNotShownInList = array(
    )     
  ;
    
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = 'r', $itemTypeName = null)
  {
//    var_dump($itemTypeName);
    
    $this->modelName = 'UllVentoryItem';
    
    if ($itemTypeName)
    {
      $this->itemType = Doctrine::getTable('UllVentoryItemType')->findOneBySlug($itemTypeName);
    }
    
//    var_dump($this->itemType>toArray());die;
    
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

    
    // Much opportunity to refactore down here...

    unset(
      $this->columnsConfig['creator_user_id'],
      $this->columnsConfig['created_at']      
    );    
    
    if ($this->requestAction == 'edit')
    {
      $this->columnsConfig['id']->setAccess('w');
      $this->columnsConfig['id']->setWidgetOption('is_hidden', true);
      $this->columnsConfig['ull_entity_id']->setWidgetOption('is_hidden', true);
    }
    else
    {
      unset(
        $this->columnsConfig['id']
      );
      $this->columnsConfig['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');      
    }
    
    $itemTypeCC = new ullColumnConfiguration();
    $itemTypeCC->setMetaWidgetClassName('ullMetaWidgetForeignKey');
    $itemTypeCC->setLabel('Type');
	  $itemTypeCC->setRelation(array(
      'model'             => 'UllVentoryItemType',
      'foreign_id'        => 'id'));
    $itemTypeCC->setWidgetOptions(array('add_empty' => true));
    $itemTypeCC->setValidatorOptions(array('required' => true));
    $itemTypeCC->setAccess($this->getDefaultAccess());
    
    
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
    $itemManufactorCC->setAccess($this->getDefaultAccess());
    
    $this->columnsConfig['ull_ventory_item_manufacturer_id'] = $itemManufactorCC;
    

    
    $this->columnsConfig['ull_ventory_item_manufacturer_id'] = $itemManufactorCC;    
        
    $this->columnsConfig['ull_ventory_item_model_id']->setAllowCreate(true);
    $this->columnsConfig['ull_ventory_item_model_id']->setWidgetOption('add_empty', true);
    
    $this->columnsConfig['ull_entity_id']->setLabel(__('Owner', null, 'common'));
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
    
    $this->columnsConfig['serial_number']->setIsInList(false);
    $this->columnsConfig['serial_number']->setLabel(__('Serial number'));
    $this->columnsConfig['comment']->setLabel(__('Comment', null, 'common'));
    
    if ($this->requestAction == 'list')
    {
      $cc = new ullColumnConfiguration();
      $cc->setMetaWidgetClassName('ullMetaWidgetForeignKey');
      $cc->setLabel(__('Location'));
      $cc->setRelation(array(
          'model'             => 'UllLocation',
          'foreign_id'        => 'id'));
      $cc->setAccess($this->getDefaultAccess());
      
      $this->columnsConfig['ull_location_id'] = $cc;
      
      $this->columnsConfig['inventory_number']->setMetaWidgetClassName('ullMetaWidgetLink');
    }      
    
    if ($this->requestAction == 'edit')
    {    
      $order = array(
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
        'inventory_number',
        'serial_number',
  //      'ull_entity_id',
  //      'ull_location_id',
        'comment'
      );
    }
    else
    {
      $order = array(
        'inventory_number',
        'ull_ventory_item_type_id',
        'ull_ventory_item_manufacturer_id',
        'ull_ventory_item_model_id',
//        'serial_number',
        'ull_entity_id',
        'ull_location_id',      
        'comment'
      );      
    }
  
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
    
    // set item type according to request param in create mode
    if ($rows instanceof Doctrine_Record && !$rows->exists())
    {
      //set type in object
      $model = new UllVentoryItemModel;
      $model->UllVentoryItemType = $this->itemType;
      $rows->UllVentoryItemModel = $model;    
    }
    
   parent::buildForm($rows);
    
//    $q = new Doctrine_Query;
//    $q
//      ->from('UllVentoryItemAttributeValue v')
////      ->where('v.UllVentoryItemTypeAttribute.UllVentoryItemType.id = ?', 1)
//    ;
//    $itemAttributeValues = $q->execute();
    
//    var_dump($itemAttributeValues->toArray());die;

    if ($this->getRequestAction() == 'edit')
    {
      $listForm = new sfForm;

      // create
      if (!$this->getRow()->exists())
      {
        foreach($this->itemType->UllVentoryItemTypeAttribute as $attribute)
        {
          $attributeValue = new UllVentoryItemAttributeValue;
          $attributeValue->UllVentoryItemTypeAttribute = $attribute;
          
          $attributeGenerator = new ullVentoryAttributeGenerator('w', $attributeValue);
          
          $listForm->embedForm(count($listForm), $attributeGenerator->getForm());          
        }
      }
      // edit
      else
      {
        foreach ($this->getRow()->UllVentoryItemAttributeValue as $attributeValue)
        {
          $attributeGenerator = new ullVentoryAttributeGenerator('w', $attributeValue);
          
          $listForm->embedForm(count($listForm), $attributeGenerator->getForm());
        }        
      }
      
      $this->getForm()->embedForm('attributes', $listForm);
      
      $memoryGenerator = new ullVentoryMemoryGenerator('w', $this->getRow());
      // set defaults
      $memory = new UllVentoryItemMemory;
      if (!$this->getRow()->exists())
      {
        $memory->transfer_at = date('Y-m-d');
        $memory->target_ull_entity_id = Doctrine::getTable('UllVentoryOriginDummyUser')->findOneByUsername('delivered')->id;
      }
      else
      {
        $memory->target_ull_entity_id = $this->getRow()->UllEntity->id;
      }
      $memoryGenerator->buildForm($memory);
      $this->getForm()->embedForm('memory', $memoryGenerator->getForm());      
    }
    
//    $attributeGenerator = new ullVentoryAttributeGenerator('w');
//    $attributeGenerator->buildForm($itemAttributeValues);
//
//    $listForm = new sfForm;
//    
//    $attributeGenerator = new ullVentoryAttributeGenerator('w');
//    $attributeGenerator->buildForm(new UllVentoryItemAttributeValue);
//    $listForm->embedForm(0, $attributeGenerator->getForm());
//    
//    $attributeGenerator2 = new ullVentoryAttributeGenerator('w');
//    $attributeGenerator2->buildForm(new UllVentoryItemAttributeValue);
//    $listForm->embedForm(1, $attributeGenerator2->getForm());
//
////    var_dump(count($listForm));
//    
//    $this->getForm()->embedForm('attributes', $listForm);
    
    
    
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