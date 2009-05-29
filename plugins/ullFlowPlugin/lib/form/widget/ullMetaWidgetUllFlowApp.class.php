<?php
/**
 * ullMetaWidgetUllFlowApp
 *
 * Used for uploads in ullFlow
 */
class ullMetaWidgetUllFlowApp extends ullMetaWidget
{
  protected function addToForm()
  {
      $this->addWidget(new ullWidgetUllFlowApp($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
  }
}

?>