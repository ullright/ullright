<?php 

/* Default text for the course cancellation SMS */

echo __(
  'Sorry, your course has been canceled. More details by email. Regards, %from_name%',
  array('%from_name%' => sfConfig::get('app_ull_course_from_name')),
  'ullCourseMessages'
);
