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
  public function __construct($itemTypeName = null, $defaultAccess = null, $requestAction = null)
  {
    $this->modelName = 'UllVentoryItem';
    
    if ($itemTypeName)
    {
      $this->itemType = Doctrine::getTable('UllVentoryItemType')->findOneBySlug($itemTypeName);
    }
    
    parent::__construct($this->modelName, $defaultAccess, $requestAction);
  }  
  
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    $this->columnsConfig = ullColumnConfigCollection::buildFor('UllVentoryItem', $this->defaultAccess, $this->requestAction);
    
//    var_dump($this->columnsConfig);die;
  }

  
  /**
   * Extends parent buildForm method
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
    
    if ($this->isAction(array('createWithType', 'edit')))
    {
      $listForm = new sfForm;

      if ($this->isAction('createWithType'))
      {
        foreach($this->itemType->UllVentoryItemTypeAttribute as $attribute)
        {
          $attributeValue = new UllVentoryItemAttributeValue;
          $attributeValue->UllVentoryItemTypeAttribute = $attribute;
          
          $attributeGenerator = new ullVentoryAttributeGenerator($attributeValue);
          
          $listForm->embedForm(count($listForm), $attributeGenerator->getForm());          
        }
      }
      
      if ($this->isEditAction()) 
      {
        foreach ($this->getRow()->UllVentoryItemAttributeValue as $attributeValue)
        {
          $attributeGenerator = new ullVentoryAttributeGenerator($attributeValue);
          
          $listForm->embedForm(count($listForm), $attributeGenerator->getForm());
        }        
      }
      
   
      
      $this->getForm()->embedForm('attributes', $listForm);
      
      $memoryGenerator = new ullVentoryMemoryGenerator();
      // set defaults
      $memory = new UllVentoryItemMemory;
      if ($this->isAction('createWithType'))
      {
        $memory->transfer_at = date('Y-m-d');
        $memory->target_ull_entity_id = Doctrine::getTable('UllVentoryOriginDummyUser')->findOneByUsername('delivered')->id;
      }
      if ($this->isEditAction())
      {
        $memory->target_ull_entity_id = $this->getRow()->UllEntity->id;
      }
      $memoryGenerator->buildForm($memory);
      $this->getForm()->embedForm('memory', $memoryGenerator->getForm());      
    }
    
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