<?php 

class BaseUllProjectReportingColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array(
      'id',
      'linked_model',
      'linked_id',
    ));
    
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
      ->setMetaWidgetClassName('ullMetaWidgetUllProject')
      ->setLabel(__('Project', null, 'ullTimeMessages'))
      ->setWidgetOption('add_empty', true)
      ->setOption('show_search_box', true)
      ->setOption('enable_inline_editing', true)
    ;
    $this['duration_seconds']
      ->setMetaWidgetClassName('ullMetaWidgetTimeDuration')
      ->setLabel(__('Duration', null, 'common'))
      ->setValidatorOption('allow_zero_duration', false)
    ;
    $this['comment']
      ->setMetaWidgetClassName('ullMetaWidgetString')
    ;
    $this['week']->disable();
    $this['created_at']->disable();
    $this['creator_user_id']->disable();
    $this['updated_at']->disable();
    $this['updator_user_id']->disable();
  }
}