<?php

class ullVentoryAttributeGenerator extends ullTableToolGenerator
{
  protected
    $columnsNotShownInList = array(
    ),
    $attributeValue     
  ;
    
  /**
   * Constructor
   *
   */
  public function __construct(UllVentoryItemAttributeValue $attributeValue, $defaultAccess = null, $requestAction = null)
  {
    $this->modelName = 'UllVentoryItemAttributeValue';
    $this->attributeValue = $attributeValue;
    
    parent::__construct($this->modelName, $defaultAccess, $requestAction);
    
    $this->buildForm($this->attributeValue);
  }  
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    $this->columnsConfig = UllVentoryItemAttributeValueColumnConfigCollection::build(
        $this->attributeValue, $this->defaultAccess, $this->requestAction);
  }

}