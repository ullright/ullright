<?php
/**
 * ullMetaWidgetAjaxSelect
 *
 */
class ullMetaWidgetAjaxSelect extends ullMetaWidgetForeignKey
{
  
  protected function configureWriteMode()
  {
    $this->columnConfig['widgetOptions']['add_empty'] = true;
    $this->columnConfig['validatorOptions']['model'] = $this->columnConfig['relation']['model'];
    
    $this->addWidget(new ullWidgetAjaxSelectWrite($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig['validatorOptions']));
  }
  
}
