<?php

class ullVentoryAttributeGenerator extends ullTableToolGenerator
{
  protected
//    $formClass = 'ullVentoryForm',
    
    $columnsNotShownInList = array(
    ),
    $attributeValue     
  ;
    
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = 'r', UllVentoryItemAttributeValue $attributeValue)
  {
    $this->modelName = 'UllVentoryItemAttributeValue';
    $this->attributeValue = $attributeValue;
    
    parent::__construct($this->modelName, $defaultAccess);
    
    $this->buildForm($this->getAttributeValue());
  }  
  
  public function getAttributeValue()
  {
    return $this->attributeValue;
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
      $this->columnsConfig['created_at'],
      $this->columnsConfig['ull_ventory_item_id']      
    );
    
    $this->columnsConfig['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');
    
    if ($this->requestAction == 'edit')
    {
      unset(
        $this->columnsConfig['updator_user_id'],
        $this->columnsConfig['updated_at']
      );
      $this->columnsConfig['id']->setAccess('w');
      $this->columnsConfig['id']->setWidgetOption('is_hidden', true);
      $this->columnsConfig['ull_ventory_item_type_attribute_id']->setWidgetOption('is_hidden', true);

      $typeAttribute = $this->getAttributeValue()->UllVentoryItemTypeAttribute;
      $this->columnsConfig['value']->setValidatorOption('required', $typeAttribute->is_mandatory);
      $this->columnsConfig['value']->setMetaWidgetClassName($typeAttribute->UllVentoryItemAttribute->UllColumnType->class);
      $this->columnsConfig['comment']->setMetaWidgetClassName('ullMetaWidgetString');
      $this->columnsConfig['comment']->setWidgetAttribute('size', 24);
    }
    else
    {
      unset(
        $this->columnsConfig['id']        
      );      
    }
    
    
    
//    var_dump($this->columnsConfig);die;
  }

}