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
      // ATTRIBUTES
      if ($this->isAction(array('createWithType', 'edit')))
      {
        $attributesForm = new sfForm;
        
        if (!$this->itemType)
        {
          $this->itemType = $this->getRow()->UllVentoryItemModel->UllVentoryItemType;
        }
        
        foreach($this->itemType->UllVentoryItemTypeAttribute as $typeAttribute)
        {
          if ($this->isAction('createWithType'))
          {
            $attributeValue = new UllVentoryItemAttributeValue;
            $attributeValue->UllVentoryItemTypeAttribute = $typeAttribute;
          }
          
          if ($this->isEditAction())
          {
            $attributeValue = UllVentoryItemAttributeValueTable::findByItemIdAndTypeAttributeIdOrCreate($this->getRow()->id, $typeAttribute->id);
                        
          }
          
          $attributeGenerator = new ullVentoryAttributeGenerator($attributeValue);
          $attributesForm->embedForm(count($attributesForm), $attributeGenerator->getForm());          
        }
      }
      
      $this->getForm()->embedForm('attributes', $attributesForm);
      
      // SOFTWARE
      if ($this->getRow()->UllVentoryItemModel->UllVentoryItemType->has_software)
      {
        $softwareForm = new sfForm;
        
        foreach(UllVentorySoftwareTable::findOrderedByName() as $software)
        {
          if ($this->isAction('createWithType'))
          {
            $itemSoftware = new UllVentoryItemSoftware;
            $itemSoftware->UllVentorySoftware = $software;
          }
          
          if ($this->isEditAction())
          {
            $itemSoftware = UllVentoryItemSoftwareTable::findByItemIdAndSoftwareIdOrCreate($this->getRow()->id, $software->id);
          }
          
          $softwareGenerator = new ullVentorySoftwareGenerator($itemSoftware);
          $softwareForm->embedForm(count($softwareForm), $softwareGenerator->getForm());
        }
        
        $this->getForm()->embedForm('software', $softwareForm);
      }
      
      
      // MEMORY
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