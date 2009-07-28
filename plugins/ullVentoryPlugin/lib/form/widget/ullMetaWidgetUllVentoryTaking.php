<?php

class ullMetaWidgetUllVentoryTaking extends ullMetaWidget
{
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetUllVentoryTaking($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());    
  }  

}