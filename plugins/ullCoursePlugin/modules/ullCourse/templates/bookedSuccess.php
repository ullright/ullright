<h1><?php echo __('Thank you for your booking!', null, 'ullCourseMessages') ?></h1>

<p>
  <?php echo __('An email containing the payment information has been sent to you', null, 'ullCourseMessages') ?>.
</p>

<p>
  <?php echo link_to(
    __('Return to the list of courses', null, 'ullCourseMessages'),
    '@ull_course_offering'
  ) ?>
</p>
