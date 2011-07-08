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
      ->setLabel(__('Booking number', null, 'ullCourseMessages'))
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

    $this['is_paid']
      ->setLabel(__('Paid', null, 'ullCourseMessages') . '?')
      ->setAjaxUpdate(true)
    ;
    
    $this['marked_as_paid_at']
      ->setLabel(__('Marked as paid at', null, 'ullCourseMessages'))
      ->setAccess('r')
    ;
    
    $this['marked_as_paid_ull_user_id']
      ->setLabel(__('Marked as paid by', null, 'ullCourseMessages'))
      ->setAccess('r')
    ;    
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'marked_as_paid_at',
        'marked_as_paid_ull_user_id'
      ));
    }
    
    if ($this->isCreateOrEditAction())
    {
    }
  }
 
}