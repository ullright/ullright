<?php echo __('Hello', null, 'common') . ' ' . $booking->Creator->display_name ?>!
    
<?php echo __('Thank you for your booking', null, 'ullCourseMessages') ?>.    
    
<?php $price = format_currency($booking->UllCourseTariff->price, 'EUR') ?>    
<?php echo __('Please transfer %amount% to our bank account', 
  array('%amount%' => $price), 'ullCourseMessages') ?>:
  
<?php echo __(sfConfig::get('app_ull_course_payment_information'), null, 'custom') ?>

<?php echo __('Do not forget to fill in: ', null, 'ullCourseMessages') ?>  

- <?php echo __('Your name', null, 'ullCourseMessages') ?>: <?php echo $booking->Creator->display_name?>

<?php $bookingNumber = str_pad($booking->id, 12, '0', STR_PAD_LEFT) ?>
- <?php echo __('Customer data', null, 'ullCourseMessages') ?>: <?php echo  $bookingNumber?> 
  
<?php echo __('Thank you', null, 'common') ?>!