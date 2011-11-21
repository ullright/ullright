<?php

class ullMetaWidgetLinkNewsletterListToSubscriber extends ullMetaWidget
{

  public function configureReadMode()
  {
    $this->addWidget(new ullWidgetLinkNewsletterListToSubscriber($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());   
  }
  
}