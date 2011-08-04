<?php

class ullWidgetLinkCourseToBooking extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value = parent::render($name, $value, $attributes, $errors);
    
    $return = '';
    
    $return .= link_to(
      __('Bookings', null, 'ullCourseMessages'),
      'ullCourseBooking/list?filter[UllCourse->is_active]=_all_&filter[ull_course_id]=' . $this->getAttribute('rel')
    );
    
    return $return;
  }
  
}