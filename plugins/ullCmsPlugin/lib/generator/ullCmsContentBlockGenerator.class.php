<?php

class ullCmsContentBlockGenerator extends ullTableToolGenerator
{
  protected
    $contentType
  ;
  
  public function __construct($contentType = null, $defaultAccess = null, $requestAction = null, $columns = array())
  {
    $this->contentType = $contentType;
    
    parent::__construct('UllCmsContentBlock', $defaultAccess, $requestAction, $columns);
  }
  
  
  
  /**
   * Check for content type specific columnsConfig
   *
   */
  protected function buildColumnsConfig()
  {
    $this->columnsConfig = ullCmsContentBlockColumnConfigCollection::build($this->contentType, $this->defaultAccess, $this->requestAction);
    
    $this->customizeColumnsConfig();
    
    $this->handleRelationColumns();
    
    $this->customizeRelationColumns();
    
    $this->handleVersionableBehaviour();

  }  
  

  
}