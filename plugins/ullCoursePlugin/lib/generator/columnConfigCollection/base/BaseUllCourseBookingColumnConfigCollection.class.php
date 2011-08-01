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
      ->setWidgetOption('add_empty', true)
    ;

    $this['ull_user_id']
      ->setLabel(__('Person', null, 'ullCoreMessages'))
      ->setWidgetOption('add_empty', true)
    ;    
    
    $this['ull_course_tariff_id']
      ->setLabel(__('Tariff', null, 'ullCourseMessages'))
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
    
    $this['is_approved']
      ->setLabel(__('Approved', null, 'ullCourseMessages') . '?')
      ->setAjaxUpdate(true)
    ;
    
  }
 
}