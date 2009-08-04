<?php
/**
 * ullMetaWidgetLink 
 * 
 * This widget is used for linking columns in the
 * result list to e.g. the show action
 */
class ullMetaWidgetLink extends ullMetaWidgetString
{
  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetAttribute('size');
    $this->columnConfig->removeWidgetAttribute('maxlength');

    $this->addWidget(new ullWidgetLink($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }  
}
