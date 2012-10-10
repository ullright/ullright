<?php

/**
 * UllUser::log contains json encoded array params for __()
 * 
 * ullMetaWidgetUllUserLogEmail decodes and translates
 * 
 * @author klemens
 *
 */
class ullMetaWidgetUllUserLog extends ullMetaWidget
{
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetUllUserLog($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
}