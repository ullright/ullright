<?php 

class BaseUllCourseBookingColumnConfigCollection extends ullColumnConfigCollection
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
    
    $this['ull_course_id']
      ->setLabel(__('Course', null, 'ullCourseMessages'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
    ;

    $this['ull_user_id']
      ->setLabel(__('Person', null, 'ullCoreMessages'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
    ;    
    
    $this['ull_course_tariff_id']
      ->setLabel(__('Tariff', null, 'ullCourseMessages'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
    ;        
    
    $this['are_terms_of_use_accepted']
      ->setLabel(__('Terms of use accepted', null, 'ullCourseMessages') . '?')
      ->setAccess('r')
    ;     

    $this['is_paid']
      ->setLabel(__('Paid', null, 'ullCourseMessages') . '?')
      ->setAjaxUpdate(true)
    ;
    
    $this['created_at']
      ->setLabel(__('Booked at', null, 'ullCourseMessages'))
      ->setWidgetOption('show_seconds', false)
    ;       
    
//    $this['is_approved']
//      ->setLabel(__('Approved', null, 'ullCourseMessages') . '?')
//      ->setAjaxUpdate(true)
//    ;

    if ($this->isCreateAction())
    {
      $this->disable(array(
        'are_terms_of_use_accepted'
      ));
    }
    
  }
  
  
 
}