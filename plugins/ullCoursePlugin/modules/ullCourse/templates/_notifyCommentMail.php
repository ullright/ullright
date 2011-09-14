<?php /* The first line is the subject */ echo __('Booking comment', null, 'ullCourseMessages') ?>

<?php echo __('A booking with a special comment has been sent for course "%course%"', array('%course%' => ullCoreTools::esc_decode($booking->UllCourse->name)), 'ullCourseMessages') ?>:


<?php echo $original_comment ?>



<?php echo __('Booking', null, 'common') ?>:
<?php echo url_for('ullCourseBooking/edit?id=' . $booking->id, true) ?> 
    




