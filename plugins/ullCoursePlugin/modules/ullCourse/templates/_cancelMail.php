<?php /* The first line is the subject */ echo __('Course cancelation', null, 'ullCourseMessages') ?> 
<?php include_partial('ullCourse/mailGreeting', array('booking' => $booking)) ?> 
    
<?php echo __('we are sorry to inform you that course "%course%" has been canceled', array('%course%' => $booking->UllCourse->name), 'ullCourseMessages') ?>.

<?php echo __('Please choose another course from our offering',  null, 'ullCourseMessages')?>: 

<?php echo url_for('@ull_course_offering', true) ?>


<?php include_partial('ullCourse/mailFooter', array('booking' => $booking)) ?>



