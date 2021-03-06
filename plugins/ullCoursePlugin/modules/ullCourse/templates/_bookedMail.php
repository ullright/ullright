<?php /* The first line is the subject */ echo __('Payment information', null, 'ullCourseMessages') ?> 
<?php include_partial('ullCourse/mailGreeting') ?> 
    
<?php echo __('thank you for booking our course', null, 'ullCourseMessages') ?> "<?php echo ullCoreTools::esc_decode($booking->UllCourse->name) ?>".

<?php if ($booking->comment): ?>
<?php echo __('Your comment', null, 'ullCourseMessages') ?>: <?php echo $booking->comment ?>
<?php endif ?>    
    
<?php $price = format_currency($booking->UllCourseTariff->price, 'EUR') ?>    
<?php echo __('Please transfer %amount% to our bank account', 
  array('%amount%' => $price), 'ullCourseMessages') ?>:
  
<?php echo __(sfConfig::get('app_ull_course_payment_information'), null, 'custom') ?>
<?php $bookingNumber = '12345' . str_pad($booking->id, 7, '0', STR_PAD_LEFT) ?>
- <?php echo __('Customer data', null, 'ullCourseMessages') ?>: <?php echo  $bookingNumber ?> 


<?php echo __('Don\'t forget to fill in', null, 'ullCourseMessages') ?>:   

- <?php echo __('Your name', null, 'ullCourseMessages') ?>: <?php echo $booking->UllUser->display_name ?> 
- <?php echo __('Your booking number', null, 'ullCourseMessages') ?>: <?php echo $booking->id ?> 
  
  
<?php echo __('Please note', null, 'ullCourseMessages') ?>:
  
<?php echo __('Please transfer the amount as soon as possible, as your spot is only fixed after payment', null, 'ullCourseMessages')?>.

<?php echo __('The spots are assigned in order of the payment date', null, 'ullCourseMessages')?>.
  

<?php include_partial('ullCourse/mailFooter', array('booking' => $booking)) ?>



