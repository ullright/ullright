<?php

class ullMetaWidgetRating extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass($this->columnConfig->getValidatorOptions()));
  }
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetRatingRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass($this->columnConfig->getValidatorOptions()));
  }
}
