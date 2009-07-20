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
    $this->columnsConfig =  Doctrine::getTable('UllVentoryItem')->getColumnsConfig($this->requestAction);
    
    
    
//    var_dump($this->columnsConfig);die;

    
    // Much opportunity to refactore down here...


    
   
    
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