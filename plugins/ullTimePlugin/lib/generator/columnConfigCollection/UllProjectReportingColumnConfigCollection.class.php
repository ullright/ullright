<?php 

class UllProjectReportingColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_user_id']->disable();
    $this['date']->disable();
    $this['ull_project_id']
      ->setLabel(__('Project', null, 'ullTimeMessages'))
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
    ;
    $this['duration_seconds']
      ->setMetaWidgetClassName('ullMetaWidgetTimeDuration')
      ->setLabel(__('Duration', null, 'common'));
    ;
    $this['week']->disable();
  }
}