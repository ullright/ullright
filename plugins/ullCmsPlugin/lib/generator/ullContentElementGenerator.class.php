<?php

class ullContentElementGenerator extends ullTableToolGenerator
{
  protected
    $elementType
  ;
  
  public function __construct($elementType = null, $defaultAccess = null, $requestAction = null, $columns = array())
  {
    $this->elementType = $elementType;
    
    parent::__construct('UllContentElement', $defaultAccess, $requestAction, $columns);
  }
  
  
  
  /**
   * Check for content type specific columnsConfig
   *
   */
  protected function buildColumnsConfig()
  {
    $this->columnsConfig = ullContentElementColumnConfigCollection::build(
      $this->elementType, $this->defaultAccess, $this->requestAction);
    
    $this->customizeColumnsConfig();
    
    $this->handleRelationColumns();
    
    $this->customizeRelationColumns();
    
    $this->handleVersionableBehaviour();

  }  
  
  
  /**
   * Globally change the name format
   * 
   * We do it here, because it would be difficult to inject the element type
   * into a custom form
   *
   * @return ullGeneratorForm
   */
  public function getForm()
  {
    $form = parent::getForm();
    $form->getWidgetSchema()->setNameFormat($this->elementType . '_fields[%s]');
    
    return $form;
  }  
  

  
}