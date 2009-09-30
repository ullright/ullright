<?php 

class BaseUllTimePeriodColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['from_date']->setLabel(__('Start date', null, 'ullTimeMessages'));
    $this['to_date']->setLabel(__('End date', null, 'ullTimeMessages'));

  }
}