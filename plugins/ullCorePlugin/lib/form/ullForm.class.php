<?php

class ullForm extends sfForm
{
//  protected
//    $row
//  ;  

  public function __construct(/*$row,*/ $defaults = array(), $options = array(), $CSRFSecret = null)
  {
//    $this->row = $row;
    
    parent::__construct($defaults, $options, $CSRFSecret);
  }  
  
  public function configure()
  {
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    if (sfContext::getInstance()->getRequest()->getParameter('action') == 'list')
    {
      $this->getWidgetSchema()->setFormFormatterName('ullList');
    }
    else
    {
      $this->getWidgetSchema()->setFormFormatterName('ullTable');
    }
    
          

    
//    var_dump($this->getWidgetSchema());
//    die;
  }
  
  public function addUllMetaWidget($fieldName, $ullMetaWidget)
  {
    $WidgetSchema     = $this->getWidgetSchema();
    $ValidatorSchema  = $this->getValidatorSchema();
    
    $WidgetSchema[$fieldName] = $ullMetaWidget->getSfWidget();
    $ValidatorSchema[$fieldName] = $ullMetaWidget->getSfValidator();
  }
}
