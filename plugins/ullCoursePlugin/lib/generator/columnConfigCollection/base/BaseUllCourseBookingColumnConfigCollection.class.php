<?php 

class BaseUllCourseBookingColumnConfigCollection extends ullColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array(
      'is_supernumerary_booked',
      'is_supernumerary_paid',
    ));
    

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

    $this['ull_course_booking_status_id']
      ->setLabel(__('Status', null, 'common'))
      ->setAccess('r');
    ;
    
    $this['are_terms_of_use_accepted']
      ->setLabel(__('Terms of use accepted', null, 'ullCourseMessages') . '?')
      ->setAccess('r')
    ;     

    $this['price_negotiated']
      ->setLabel(__('Price negotiated', null, 'ullCourseMessages'))
      ->setHelp(__('Optional, default is the price of the selected tariff. You can enter an individual special price here.', null, 'ullCourseMessages'))
      ->setIsRequired(false)
    ;    
    
    $this['is_paid']
      ->setLabel(__('Paid', null, 'ullCourseMessages') . '?')
      ->setAjaxUpdate(true)
    ;
    
    $this['paid_at']
      ->setLabel(__('Paid at', null, 'ullCourseMessages'))
      ->setHelp(__('Optional, default is the current date. Give a different date e.g. in case of manuall booking', null, 'ullCourseMessages'))
    ;    
    
    $this['price_paid']
      ->setLabel(__('Price paid', null, 'ullCourseMessages'))
      ->setHelp(__('Optional, in case of a partial payment, you can enter the amount here.', null, 'ullCourseMessages' ))
    ;
    
    $this['created_at']
      ->setLabel(__('Booked at', null, 'ullCourseMessages'))
      ->setWidgetOption('show_seconds', false)
    ;       
    
//    $this['is_approved']
//      ->setLabel(__('Approved', null, 'ullCourseMessages') . '?')
//      ->setAjaxUpdate(true)
//    ;

    $this->order(array(
      'basic' => array(
        'ull_course_id',
        'ull_course_tariff_id',
        'ull_user_id',
        'is_paid',
      ),
      'optional' => array(
        'ull_course_booking_status_id',
        'paid_at',
        'price_negotiated',
        'price_paid',
      ),
      'comment' => array(
        'comment'
      ),
    ));
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'are_terms_of_use_accepted',
        'ull_course_booking_status_id',
        'is_active',
      ));
    }
    
  }
  
  
 
}