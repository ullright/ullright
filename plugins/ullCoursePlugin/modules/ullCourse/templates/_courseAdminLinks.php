<div class="ull_course_admin_links">

  <ul>
  
    <?php if (UllUserTable::hasPermission('ull_course_booking_list')): ?>
      <li>
        <?php echo link_to(
          __('Show bookings', null, 'ullCourseMessages'),
          'ullCourseBooking/list?filter[ull_course_id]=' . $object->id
        )?>
      </li>
    <?php endif ?>
    
    <?php if (UllUserTable::hasPermission('ull_course_booking_edit')): ?>
      <li>
        <?php echo link_to(
          __('Create booking', null, 'ullCourseMessages'),
          'ullCourseBooking/create?course=' . $object->slug
        )?> 
      </li>
    <?php endif ?>
    
    <?php if (UllUserTable::hasPermission('ull_course_info')): ?>
      <li>
        <?php echo link_to(
          __('Participant list', null, 'ullCourseMessages'),
          'ullCourse/info?slug=' . $object->slug
        )?> 
      </li>
    <?php endif ?>
    
  </ul>
              
</div>           
