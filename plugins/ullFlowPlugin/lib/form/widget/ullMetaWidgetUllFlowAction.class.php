<?php
/**
 * ullMetaWidgetUllFlowAction
 *
 */
class ullMetaWidgetUllFlowAction extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new ullWidgetUllFlowAction($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetUllFlowActionRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
  }
}

?>