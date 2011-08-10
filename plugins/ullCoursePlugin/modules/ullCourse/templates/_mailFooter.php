<?php echo __('Links', null, 'common') ?>:

- <?php echo __('Course details', null, 'ullCourseMessages') ?>: <?php echo url_for('ullCourse/show?slug=' . $booking->UllCourse->slug, true) ?> 
- <?php echo __('Terms of use', null, 'ullCourseMessages') ?>: <?php echo url_for(sfConfig::get('ull_course_terms_of_use_link', 'ullAdmin/about'), true) ?> 
  

<?php echo __('Contact', null, 'common') ?>:

<?php echo __(sfConfig::get('app_ull_course_contact'), null, 'custom') ?>