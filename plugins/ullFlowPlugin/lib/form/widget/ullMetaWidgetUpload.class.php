<?php
/**
 * ullMetaWidgetWikiLink
 *
 * Used for uploads in ullFlow
 */
class ullMetaWidgetWikiLink extends ullMetaWidget
{
  public function __construct($columnConfig = array())
  {
    if ($columnConfig['access'] == 'w')
    {
      $this->sfWidget = new ullWidgetWikiLink($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorString($columnConfig['validatorOptions']);
    }
    else
    {
      $this->sfWidget = new ullWidgetWikiLinkRead($columnConfig['widgetOptions'], $columnConfig['widgetAttributes']);
      $this->sfValidator = new sfValidatorPass();
    }

  }
}

?>