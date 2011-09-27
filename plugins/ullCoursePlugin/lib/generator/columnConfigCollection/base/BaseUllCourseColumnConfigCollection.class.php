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
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setWidgetOption('CustomConfigurationsPath', '/ullCmsPlugin/js/FCKeditor_config.js')      
      ->setWidgetOption('width', 650)
      ->setWidgetOption('height', 350)
      ->setHelp(__('[SHIFT %arrow%] - [ENTER] creates a single line break', array('%arrow%' => '&#8657;'), 'ullCoreMessages'))
//      ->setWidgetAttribute('cols', 80)
//      ->setWidgetAttribute('rows', 10)
      ->setIsRequired(true)
    ;    
    
    $this['trainer_ull_user_id']
      ->setLabel(__('Trainer', null, 'ullCourseMessages'))
      ->setOption('filter_users_by_group', 'Trainers')
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
      ->setIsRequired(true)
      ->setHelp(__('Add or edit trainers as normal users in the %admin% by making them members of the group "Trainers" and by using the "Comment" field for the introduction', 
        array('%admin%' => ull_link_to(__('Admininistration', null, 'ullCoreMessages'), 'ullAdmin/index', array('link_new_window' => true))),
        'ullCourseMessages')) 
    ;

    $this['begin_date']
      ->setLabel(__('Begin date', null, 'ullCourseMessages'))
      ->setOption('show_weekday', true)
      ->setIsRequired(true)
    ;       
    
    $this['end_date']
      ->setLabel(__('End date', null, 'ullCourseMessages'))
      ->setIsRequired(true)
    ;

    $this['is_bookable']
      ->setLabel(__('Is bookable', null, 'ullCourseMessages'))
      ->setHelp(__('Allow customers to book this course', null, 'ullCourseMessages'))
    ;    
    
    $this['is_active']
      ->setLabel(__('Is online', null, 'common'))
    ;
    
    $this['is_canceled']
      ->setLabel(__('Is canceled', null, 'ullCourseMessages'))
    ;    
    
    $this['ull_course_status_id']
      ->setLabel(__('Status', null, 'common'))
      ->setAccess('r')
    ;
    
    $this['begin_time']
      ->setLabel(__('Begin time', null, 'ullCourseMessages'))
      ->setIsRequired(true)
    ;    
    
    $this['end_time']
      ->setLabel(__('End time', null, 'ullCourseMessages'))
      ->setIsRequired(true)
    ;

    $this['number_of_units']
      ->setLabel(__('Number of units', null, 'ullCourseMessages'))
      ->setIsRequired(true)
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
    
    $this->create('link_to_bookings')
      ->setMetaWidgetClassName('ullMetaWidgetLinkCourseToBooking')
      ->setLabel(' ')
      ->setIsArtificial(true)
      ->setAccess('r')
      ->setInjectIdentifier(true)
    ;

    if ($this->isCreateOrEditAction())
    {
      $this->order(array(
        'basics' => array(
          'id',
          'name',
          'description',
        ),
        'temporal' => array(
          'begin_date',
          'begin_time',
          'end_time',
          'end_date',
          'number_of_units',
        ),
        'service' => array(
          'UllCourseTariff',
          'min_number_of_participants',
          'max_number_of_participants',
        ),                                
        'proxies' => array(
          'ull_course_status_id',
          'proxy_number_of_participants_applied',
          'proxy_number_of_participants_paid',
          'proxy_turnover',
//          'link_to_bookings'
        ),
        'status' => array(
          'trainer_ull_user_id',
          'duplicate_tags_for_search',
          'is_active',        
          'is_bookable',
          'is_canceled',
        ),


      ));
    }
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'proxy_number_of_participants_applied',
        'proxy_number_of_participants_paid',
        'proxy_turnover',
//        'link_to_bookings',
        'ull_course_status_id',
        'is_canceled',
      ));
    }
    
    if (!$this->isCreateOrEditAction())
    {
      $this['proxy_turnover']
        // right align in list view
        ->setWidgetAttribute('class', 'ull_widget_time')
      ;      
    }
    
    if ('offering' == sfContext::getInstance()->getActionName())
    {
      $this['name']
        ->setMetaWidgetClassName('ullMetaWidgetLink')
      ;
      
      $this['trainer_ull_user_id']
        ->setMetaWidgetClassName('ullMetaWidgetForeignKey')
      ;
    }
    
    if ($this->isListAction())
    {
      $this['name']
        ->setMetaWidgetClassName('ullMetaWidgetLink')
        ->setLabel(__('Course', null, 'ullCourseMessages'))
      ;
      
      $this['begin_date']
        ->setLabel(__('Begin', null, 'ullCourseMessages'))
      ;      
      
      $this['min_number_of_participants']
        ->setLabel(__('Minimum', null, 'ullCourseMessages'))
      ;
      
      $this['proxy_number_of_participants_applied']
        ->setLabel(__('Applied', null, 'ullCourseMessages'))
      ;
      
      $this['proxy_number_of_participants_paid']
        ->setLabel(__('Paid', null, 'ullCourseMessages'))
      ;
      
      
    }
  }
 
}