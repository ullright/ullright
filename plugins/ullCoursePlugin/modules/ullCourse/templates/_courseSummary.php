<div class="ull_course_summary">

<p>
  <?php if ($course->isMultiDay()): ?>
    <?php echo __('%units% units', array('%units%' => $course['number_of_units']), 'ullCourseMessages') ?>
  <?php endif ?>   
  
  <?php if ($course->isMultiDay()): ?>
    <?php echo __('from', null, 'common') ?>
  <?php else: ?>
    <?php echo __('on', null, 'common') ?>
  <?php endif ?>
  
  <?php echo ull_format_date($course['begin_date'], false, true) ?>
  
  <?php if ($course->isMultiDay()): ?>
    <?php echo __('to', null, 'common') ?>
    <?php echo ull_format_date($course['end_date'], false, true) ?>
  <?php endif?>
  
  <?php echo __('Time', null, 'common') ?>:
  <?php echo ull_format_time($course['begin_time']) ?> 
  <?php echo __('to', null, 'common') ?>
  <?php echo ull_format_time($course['end_time']) ?>  
</p>

<p>
  <?php echo __('Trainer', null, 'ullCourseMessages') ?>: 
  <?php echo $course['Trainer']['display_name'] ?> &nbsp;  
  <?php echo $course['Trainer']['email'] ?> &nbsp; 
  <?php echo $course['Trainer']['mobile_number'] ?>
</p>

<p>
  <?php echo __('Bookings', null, 'ullCourseMessages') ?>: 
    <?php echo $course->proxy_number_of_participants_applied ?> &nbsp;
  <?php echo __('Paid', null, 'ullCourseMessages') ?>: 
    <?php echo $course->proxy_number_of_participants_paid ?> &nbsp;
  <?php echo __('Free spots', null, 'ullCourseMessages') ?>: 
    <?php echo $course->proxy_number_of_spots_free ?> &nbsp;    
  <?php echo __('Status', null, 'common') ?>: <?php echo $course->UllCourseStatus ?>
</p>

</div>