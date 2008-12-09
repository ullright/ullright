<?php

class ullFlowUploadTransferForm extends sfForm
{
  protected
    $column
  ;
  
  public function __construct($column, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    parent::__construct();
    
    $this->column = $column;
  }  
  
  public function configure()
  {
    $this->setWidgets(array(
        'doc'                             => new sfWidgetFormInputHidden(),
        'fields[' . $this->column . ']'   => new sfWidgetFormInputHidden(),
    ));
    
    $this->widgetSchema->setNameFormat('fields[%s]');
  
    $this->setValidators(array(
        'doc'     => new sfValidatorString(),
        'fields'  => new sfValidatorString(),
    ));
  }
}
