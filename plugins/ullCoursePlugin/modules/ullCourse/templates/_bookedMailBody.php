<?php echo __('Hello', null, 'common') . ' ' . $booking->Creator->display_name ?>,
    
<?php echo __('Thank you for your booking', null, 'ullCourseMessages') ?>.    
    
<?php echo __('Please transfer %amount% to our bank account', 
  array('%amount%' => $booking->UllCourseTariff->price), 'ullCourseMessages') ?>.
  
<?php echo __('Thank you', null, 'common') ?>!