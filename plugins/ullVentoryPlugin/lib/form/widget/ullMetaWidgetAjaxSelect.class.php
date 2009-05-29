<?php
/**
 * ullMetaWidgetAjaxSelect
 *
 */
class ullMetaWidgetAjaxSelect extends ullMetaWidgetForeignKey
{
  
  protected function configureWriteMode()
  {
    $this->columnConfig->setWidgetOption('add_empty', true);
    $relation = $this->columnConfig->getRelation();
    $this->columnConfig->setValidatorOption('model', $relation['model']);
    
    $this->addWidget(new ullWidgetAjaxSelectWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
  }
  
}
