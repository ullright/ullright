<?php 

class UllVentoryItemAttributeValueColumnConfigCollection extends ullColumnConfigCollection
{
  protected
    $attributeValue
  ;
  
  public function __construct($modelName, $attributeValue, $defaultAccess = null, $requestAction = null)
  {
    $this->attributeValue = $attributeValue;
    parent::__construct($modelName, $defaultAccess, $requestAction);
  }

  public static function build($attributeValue, $defaultAccess = null, $requestAction = null)
  {
    $c = new self('UllVentoryItemAttributeValue', $attributeValue, $defaultAccess, $requestAction);
    $c->buildCollection();
    
    return $c;
  }
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('creator_user_id', 'created_at', 'ull_ventory_item_id'));
    
    $this['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');

    if ($this->isListAction())
    {
      $this['id']->disable();
    }

    if ($this->isAction(array('createWithType', 'edit')))
    {
      $this->disable(array('updator_user_id', 'updated_at'));
      
      $this['id']
        ->setAccess('w')
        ->setWidgetOption('is_hidden', true)
      ;
      $this['ull_ventory_item_type_attribute_id']->setWidgetOption('is_hidden', true);

      $typeAttribute = $this->getAttributeValue()->UllVentoryItemTypeAttribute;
      $this['value']
        ->setValidatorOption('required', $typeAttribute->is_mandatory)
        ->setMetaWidgetClassName($typeAttribute->UllVentoryItemAttribute->UllColumnType->class)
      ;
      
      $this['comment']
        ->setMetaWidgetClassName('ullMetaWidgetString')
        ->setWidgetAttribute('size', 24)
      ;
    }
    
  }     
  
  public function getAttributeValue()
  {
    return $this->attributeValue;
  }  
  
}