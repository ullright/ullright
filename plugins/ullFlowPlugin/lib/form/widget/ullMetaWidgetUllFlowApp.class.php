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
      $this->addWidget(new ullWidgetUllFlowApp($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
  }
}

?>