<?php

class ullVentoryMemoryGenerator extends ullTableToolGenerator
{
  protected
    $columnsNotShownInList = array(
    )
  ;
    
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = null, $requestAction = null)
  {
    $this->modelName = 'UllVentoryItemMemory';
    parent::__construct($this->modelName, $defaultAccess, $requestAction);
  }  
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    $this->columnsConfig = ullColumnConfigCollection::buildFor('UllVentoryItemMemory', $this->defaultAccess, $this->requestAction);
  }  

}