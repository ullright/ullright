<?php /* The first line is the subject */ echo __('Payment received', null, 'ullCourseMessages') ?> 
<?php include_partial('ullCourse/mailGreeting') ?> 
    
<?php echo __('thank you for your payment of %price% for course "%course%"', array(
  '%course%' => $booking->UllCourse->name,
  '%price%' => format_currency($booking->price_negotiated, 'EUR'),
), 'ullCourseMessages') ?>.    

<?php echo __('We\'re looking forward to welcome you on %date% at %time%', array(
  '%date%' => ull_format_date($booking->UllCourse->begin_date),
  '%time%' =>ull_format_time($booking->UllCourse->begin_time),
), 'ullCourseMessages') ?>.


<?php include_partial('ullCourse/mailFooter', array('booking' => $booking)) ?>



