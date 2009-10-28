<?php

class ullMetaWidgetUllEntityType extends ullMetaWidget
{
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetUllEntityTypeRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());    
  }
}
