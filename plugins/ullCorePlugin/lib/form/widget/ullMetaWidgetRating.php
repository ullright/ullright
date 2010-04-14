<?php

/**
 * This meta widget provides a star-select rating bar.
 * In read mode, the bar is static; in write mode,
 * a rating can be dynamically selected and the result
 * is submitted via ajax.
 * 
 * Usually used in combination with the ullRateable
 * behavior. Requires JS.
 */
class ullMetaWidgetRating extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetRatingWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    //TODO: needs a validator!
    $this->addValidator(new sfValidatorPass($this->columnConfig->getValidatorOptions()));
  }
  
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetRatingRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass($this->columnConfig->getValidatorOptions()));
  }
}
