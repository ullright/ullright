<?php 

class UllProjectReportingColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_project_id']->setLabel(__('Project', null, 'ullTimeMessages'));
    $this['duration_seconds']
      ->setMetaWidgetClassName('ullMetaWidgetTimeDuration')
      ->setLabel(__('Duration', null, 'common'));
    ;
    $this['week']->disable();
  }
}