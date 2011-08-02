<h1><?php echo __('Tarif selection', null, 'ullCourseMessages') ?></h1>

<p>
  <?php echo __('Please select the correct tariff', null, 'ullCourseMessages') ?>:
</p>

<ul>
<?php foreach ($doc->UllCourseTariff as $tariff): ?>
  <li>
    <?php echo link_to(
      $tariff['display_name'],
      'ullCourse/confirmation?slug=' . $doc['slug'] . '&ull_course_tariff_id=' . $tariff['id']
    ) ?>
  </li>
<?php endforeach ?>
</ul>

