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
    $this->getWidgetSchema()->setFormFormatterName('ullTable');
    
          

    
//    var_dump($this->getWidgetSchema());
//    die;
  }
  
  public function addUllWidgetWrapper($fieldName, $ullWidgetWrapper)
  {
    $WidgetSchema     = $this->getWidgetSchema();
    $ValidatorSchema  = $this->getValidatorSchema();
    
    $WidgetSchema[$fieldName] = $ullWidgetWrapper->getSfWidget();
    $ValidatorSchema[$fieldName] = $ullWidgetWrapper->getSfValidator();
  }
}
