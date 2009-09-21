<?php 

class UllProjectReportingColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['id']->disable();
    if ($this->isListAction())
    {
      $this['ull_user_id']->disable();
      $this['date']->disable();
    }
    else
    {
      $this['ull_user_id']->setAccess('r');
      $this['date']->setAccess('r');
    }
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
    $this['created_at']->disable();
    $this['creator_user_id']->disable();
    $this['updated_at']->disable();
    $this['updator_user_id']->disable();
  }
}