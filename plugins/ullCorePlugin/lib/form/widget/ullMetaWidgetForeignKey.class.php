<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  
  protected function configure()
  {
    $this->columnConfig['widgetOptions']['model'] = $this->columnConfig['relation']['model'];
  }
    

  protected function configureWriteMode()
  {
    $this->columnConfig['validatorOptions']['model'] = $this->columnConfig['relation']['model'];
    
    $this->addWidget(new sfWidgetFormDoctrineSelect($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig['validatorOptions']));
  }
  
  
  protected function configureReadMode()
  {
    //ullWidgetForeignKey doesn't support option 'add_empty'
    unset($this->columnConfig['widgetOptions']['add_empty']);
    
    $this->addWidget(new ullWidgetForeignKey($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());
  }

}