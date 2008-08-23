<?php

class TableToolForm extends UllForm
{
  
  protected
      $tableName
  ;
  
  public function __construct($tableName, $columnInfo = array(), $row, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->tableName = $tableName;
    parent::__construct($columnInfo, $row, $defaults, $options, $CSRFSecret);
  }
  
  public function configure()
  {
    $this->setWidgets(array(
          'table_name' => new sfWidgetFormInput
    ));
    
    $this->setValidators(array(
          'table_name' => new sfValidatorPass
    ));
    
    $this->setDefault('table_name', $this->tableName);

    parent::configure();
  }
}
