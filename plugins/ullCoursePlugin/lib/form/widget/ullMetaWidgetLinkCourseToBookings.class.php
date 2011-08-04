<?php

class ullMetaWidgetLinkCourseToBooking extends ullMetaWidget
{

  public function configureReadMode()
  {
    $this->addWidget(new ullWidgetLinkCourseToBooking($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());   
  }
  
}