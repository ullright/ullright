<?php
/**
 * ullMetaWidgetWikiLink
 *
 * Used for uploads in ullFlow
 */
class ullMetaWidgetWikiLink extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new ullWidgetWikiLink($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorString($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetWikiLinkRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }

  }
}

?>