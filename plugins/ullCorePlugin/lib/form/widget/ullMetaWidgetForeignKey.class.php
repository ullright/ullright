<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  protected function configure()
  {
    $relation = $this->columnConfig->getRelation();
    if (!$this->columnConfig->getWidgetOption('model'))
    {
      $this->columnConfig->setWidgetOption('model', $relation['model']);
    }
  }
    
  protected function configureWriteMode()
  {
//    var_dump($this->columnConfig);die;
    $validatorOptions = $this->columnConfig->getValidatorOptions();
    $relation = $this->columnConfig->getRelation();
    $this->columnConfig->setValidatorOption('model', $relation['model']);
    
    // the dropdown field can't be mandatory in case of allowCreate
    if ($this->columnConfig->getAllowCreate() == true)
    {
      $this->columnConfig->setValidatorOption('required', false);
    }
    
    $this->addWidget(new sfWidgetFormDoctrineSelect($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
    
    if ($this->columnConfig->getAllowCreate() == true)
    {
      $this->columnConfig->removeWidgetOption('add_empty');
      $this->columnConfig->removeWidgetOption('model');
      $this->columnConfig->removeValidatorOption('model');
      $createColumnName = $this->columnName . '_create';
      $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()), $createColumnName);
      $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()), $createColumnName);
      $this->form->getWidgetSchema()->moveField($createColumnName, 'after', $this->columnName);
      
      // add a "one of the two fields is mandatory" validator
      $this->form->mergePostValidator(new sfValidatorOr(array(
        new sfValidatorSchemaFilter(
          $this->columnName, 
          new sfValidatorString(array('required' => true), array('required' => __('Please select a value or enter a new one', null, 'common')))),
        new sfValidatorSchemaFilter(
          $createColumnName, 
          new sfValidatorString(array('required' => true), array('required' => __('Please select a value or enter a new one', null, 'common')))),
      ))); 
    }
  }
  
  
  protected function configureReadMode()
  {
    //ullWidgetForeignKey doesn't support option 'add_empty'
    $this->columnConfig->removeWidgetOption('add_empty');
    
    $this->addWidget(new ullWidgetForeignKey($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }

  public function getSearchType()
  {
    return 'foreign';
  }
}