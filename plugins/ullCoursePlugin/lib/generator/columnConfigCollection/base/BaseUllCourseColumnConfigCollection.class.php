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
      ->setLabel(__('Course number', null, 'ullCourseMessages'))
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

    $this['ull_cms_item_id']
      ->setLabel(__('Parent', null, 'ullCmsMessages'))
      ->setWidgetOption('show_search_box', true)
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
    
//    $this['price_normal']
//      ->setLabel(__('Normal price', null, 'ullCourseMessages'))
//    ;
//
//    $this['price_reduced']
//      ->setLabel(__('Reduced price', null, 'ullCourseMessages'))
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
      ->setAccess('r')
    ;
    
    $this->useManyToManyRelation('UllCourseTariff');
    $this['UllCourseTariff']
      ->setLabel(__('Tariffs', null, 'ullCourseMessages'))
      ->setWidgetOption('enable_inline_editing', true)
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
          'ull_cms_item_id',
          'sequence',
          'is_active',        
        ),
        'temporal' => array(
          'begin_date',
          'end_date',
          'begin_time',
          'end_time',
          'number_of_units',
        ),
        'service' => array(
          'UllCourseTariff',
//          'price_normal',
//          'price_reduced',  
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
  }
 
}