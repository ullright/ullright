<?php

class ullForm extends sfFormDoctrine
{
  protected 
    $modelName
  ;

  public function __construct($object = null, $options = array(), $CSRFSecret = null)
  {
    $this->modelName = get_class($object);
    
    parent::__construct($object, $options, $CSRFSecret);
  }  
  
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    //TODO: refactor
    if (sfContext::getInstance()->getRequest()->getParameter('action') == 'list')
    {
      $this->getWidgetSchema()->setFormFormatterName('ullList');
    }
    else
    {
      $this->getWidgetSchema()->setFormFormatterName('ullTable');
    }
  }
  
  public function getModelName()
  {
    return $this->modelName;
  }  
  
  public function addUllMetaWidget($fieldName, $ullMetaWidget)
  {
    $WidgetSchema     = $this->getWidgetSchema();
    $ValidatorSchema  = $this->getValidatorSchema();
    
    $WidgetSchema[$fieldName] = $ullMetaWidget->getSfWidget();
    $ValidatorSchema[$fieldName] = $ullMetaWidget->getSfValidator();
  }
}
