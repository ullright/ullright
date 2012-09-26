<?php

class ullCmsElementGenerator extends ullTableToolGenerator
{
  protected
    $elementType
  ;
  
  public function __construct($elementType = null, $defaultAccess = null, $requestAction = null, $columns = array())
  {
    $this->elementType = $elementType;
    
    parent::__construct('UllCmsElement', $defaultAccess, $requestAction, $columns);
  }
  
  
  
  /**
   * Check for content type specific columnsConfig
   *
   */
  protected function buildColumnsConfig()
  {
    $this->columnsConfig = ullCmsElementColumnConfigCollection::build(
      $this->elementType, $this->defaultAccess, $this->requestAction);
    
    $this->customizeColumnsConfig();
    
    $this->handleRelationColumns();
    
    $this->customizeRelationColumns();
    
    $this->handleVersionableBehaviour();

  }  
  

  
}