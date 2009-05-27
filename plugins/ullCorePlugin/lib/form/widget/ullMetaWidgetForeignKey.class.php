<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  
  protected function configure()
  {
    $this->columnConfig['widgetOptions']['model'] = $this->columnConfig['relation']['model'];
  }
    

  protected function configureWriteMode()
  {
//    var_dump($this->columnConfig);die;
    $this->columnConfig['validatorOptions']['model'] = $this->columnConfig['relation']['model'];
    
    // the dropdown field can't be mandatory in case of allowCreate
    if (isset($this->columnConfig['allowCreate']))
    {
      $this->columnConfig['validatorOptions']['required'] = false;
    }
    
    $this->addWidget(new sfWidgetFormDoctrineSelect($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig['validatorOptions']));
    
    if (isset($this->columnConfig['allowCreate']))
    {
      unset($this->columnConfig['widgetOptions']['add_empty']);
      unset($this->columnConfig['widgetOptions']['model']);
      unset($this->columnConfig['validatorOptions']['model']);
      $createColumnName = $this->columnName . '_create';
      $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']), $createColumnName);
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']), $createColumnName);
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
    unset($this->columnConfig['widgetOptions']['add_empty']);
    
    $this->addWidget(new ullWidgetForeignKey($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());
  }

  public function getSearchPrefix()
  {
    return 'foreign';
  }
}