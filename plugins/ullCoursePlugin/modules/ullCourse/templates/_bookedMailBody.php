<?php echo __('Hello', null, 'common') . ' ' . $booking->Creator->display_name ?>,
    
<?php echo __('thank you for your booking our course', null, 'ullCourseMessages') ?> "<?php echo $booking->UllCourse->name ?>".    
    
<?php $price = format_currency($booking->UllCourseTariff->price, 'EUR') ?>    
<?php echo __('Please transfer %amount% to our bank account', 
  array('%amount%' => $price), 'ullCourseMessages') ?>:
  
<?php echo __(sfConfig::get('app_ull_course_payment_information'), null, 'custom') ?>


<?php echo __('Don\'t forget to fill in', null, 'ullCourseMessages') ?>:   

- <?php echo __('Your name', null, 'ullCourseMessages') ?>: <?php echo $booking->Creator->display_name ?> 
- <?php echo __('Your booking number', null, 'ullCourseMessages') ?>: <?php echo $booking->id ?> 
<?php $bookingNumber = '12345' . str_pad($booking->id, 7, '0', STR_PAD_LEFT) ?>
- <?php echo __('Customer data', null, 'ullCourseMessages') ?>: <?php echo  $bookingNumber ?>  
  
<?php echo __('Bookings are orderd by the incoming tranfer date', null, 'ullCourseMessages') ?>.  


<?php echo __('Links', null, 'common') ?>:

- <?php echo __('Course details', null, 'ullCourseMessages') ?>: <?php echo url_for('ullCourse/show?slug=' . $booking->UllCourse->slug, true) ?> 
- <?php echo __('Terms of use', null, 'ullCourseMessages') ?>: <?php echo url_for(sfConfig::get('ull_course_terms_of_use_link', 'ullAdmin/about'), true) ?> 
  
