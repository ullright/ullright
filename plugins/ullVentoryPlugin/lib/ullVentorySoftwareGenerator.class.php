<?php

class ullVentorySoftwareGenerator extends ullTableToolGenerator
{
  protected
    $columnsNotShownInList = array(
    ),
    $itemSoftware
  ;
    
  /**
   * Constructor
   *
   */
  public function __construct(UllVentoryItemSoftware $itemSoftware, $defaultAccess = null, $requestAction = null)
  {
    $this->modelName = 'UllVentoryItemSoftware';
    $this->itemSoftware = $itemSoftware;
    
    parent::__construct($this->modelName, $defaultAccess, $requestAction);
    
    $this->formClass = 'ullVentoryItemSoftwareGeneratorForm';
    
    $this->buildForm($this->itemSoftware);
  }  
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    $this->columnsConfig = UllVentoryItemSoftwareColumnConfigCollection::build(
        $this->itemSoftware, $this->defaultAccess, $this->requestAction);
  }

}