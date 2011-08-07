<?php

class ullMetaWidgetCourseStatus extends ullMetaWidget
{
  
  public function configureReadMode()
  {
    $this->addWidget(new ullWidgetCourseStatus($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass()); 
  }
  
}