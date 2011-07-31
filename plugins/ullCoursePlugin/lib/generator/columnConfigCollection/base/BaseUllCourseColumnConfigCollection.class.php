<?php 

class BaseUllCourseColumnConfigCollection extends ullColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {

    $this['id']
      ->setLabel(__('No.', null, 'common'))
    ;
    
    $this['name']
      ->setLabel(__('Name', null, 'ullCoreMessages'))
    ;    
    
    $this['description']
//      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
//      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')      
//      ->setWidgetOption('width', 650)
//      ->setWidgetOption('height', 250)
    ;    
    
    $this['trainer_ull_user_id']
      ->setLabel(__('Trainer', null, 'ullCourseMessages'))
      ->setWidgetOption('add_empty', true)
    ;

    $this['begin_date']
      ->setLabel(__('Begin date', null, 'ullCourseMessages'))
    ;       
    
    $this['end_date']
      ->setLabel(__('End date', null, 'ullCourseMessages'))
    ;    
    
    $this['begin_time']
      ->setLabel(__('Begin time', null, 'ullCourseMessages'))
    ;    
    
    $this['end_time']
      ->setLabel(__('End time', null, 'ullCourseMessages'))
    ;

    $this['number_of_units']
      ->setLabel(__('Number of units', null, 'ullCourseMessages'))
    ;        
    
    $this['is_equipment_included']
      ->setLabel(__('Equipment included', null, 'ullCourseMessages') . '?')
    ;
    
    $this['is_admission_included']
      ->setLabel(__('Admission included', null, 'ullCourseMessages') . '?')
    ;    
    
    $this['min_number_of_participants']
      ->setLabel(__('Min. participants', null, 'ullCourseMessages'))
    ;
    
    $this['max_number_of_participants']
      ->setLabel(__('Max. participants', null, 'ullCourseMessages'))
    ;
    
    $this['proxy_number_of_participants_applied']
      ->setLabel(__('Participants applied', null, 'ullCourseMessages'))
      ->setAccess('r')
    ;    
    
    $this['proxy_number_of_participants_paid']
      ->setLabel(__('Participants paid', null, 'ullCourseMessages'))
      ->setAccess('r')
    ;

    $this['proxy_turnover']
      ->setLabel(__('Turnover', null, 'ullCourseMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetFloat')
      ->setAccess('r')
    ;
    
    $this->useManyToManyRelation('UllCourseTariff');
    $this['UllCourseTariff']
      ->setLabel(__('Tariffs', null, 'ullCourseMessages'))
      ->setWidgetOption('enable_inline_editing', true)
    ;
    
    $this['duplicate_tags_for_search']
      ->setLabel('Tags')
      ->setMetaWidgetClassName('ullMetaWidgetTaggable')
      ->setOption('tagging_options', array('model' => 'UllCourse'))
    ;    

    if ($this->isCreateOrEditAction())
    {
      $this->order(array(
        'basics' => array(
          'id',
          'name',
          'description',
          'trainer_ull_user_id',
        ),
        'status' => array(
          'duplicate_tags_for_search',
          'is_active',        
        ),
        'temporal' => array(
          'begin_date',
          'begin_time',
          'end_date',        
          'end_time',
          'number_of_units',
        ),
        'service' => array(
          'UllCourseTariff',
          'is_equipment_included',
          'is_admission_included',
          'min_number_of_participants',
          'max_number_of_participants',
        ),
        'proxies' => array(
          'proxy_number_of_participants_applied',
          'proxy_number_of_participants_paid',
          'proxy_turnover',
        )
      ));
    }
    
    if (!$this->isCreateOrEditAction())
    {
      $this['proxy_turnover']
        // right align in list view
        ->setWidgetAttribute('class', 'ull_widget_time')
      ;      
    }
  }
 
}