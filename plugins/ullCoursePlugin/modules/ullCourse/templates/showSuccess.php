<h1><?php echo $doc['name'] ?></h1>

<p><?php echo $doc['description'] ?></p>

<h2><?php echo __('Date', null, 'common')?></h2>

<p>
  <?php echo ull_format_date($doc['begin_date'], false, true) ?>
  <?php if ($doc->isMultiDay()): ?>
    <?php echo __('to', null, 'common') ?>
    <?php echo ull_format_date($doc['end_date'], false, true) ?>
  <?php endif?>
</p>

<p>
  <?php if ($doc->isMultiDay()): ?>
    <?php echo __('%units% units', array('%units%' => $doc['number_of_units']), 'ullCourseMessages') ?>
  <?php endif ?>    
</p>

<p>
  <?php echo __('Time', null, 'common') ?>:
  <?php echo ull_format_time($doc['begin_time']) ?> 
  <?php echo __('to', null, 'common') ?>
  <?php echo ull_format_time($doc['end_time']) ?>
</p>

<h2><?php echo __('Trainer', null, 'ullCourseMessages') ?></h2>

<?php $photoWidget = new ullWidgetPhoto() ?>
<p><?php echo $doc['Trainer']['display_name'] ?></p>
<p><?php echo $doc['Trainer']['comment'] ?></p>
<p><?php echo $photoWidget->render(null, $doc['Trainer']['photo']) ?></p>

<h2><?php echo __('Booking', null, 'ullCourseMessages') ?></h2>

<?php if (!$doc->isFullyBooked()): ?>
  <p>
    <?php echo __(
      '%current% from %total% spots available', 
      array(
        '%current%' => $doc->getSpotsAvailable(), 
        '%total%' => $doc['max_number_of_participants']
      ), 
      'ullCourseMessages') 
    ?>
  </p>
<?php endif ?>

<?php if ($doc->isInsufficientParticipants()): ?>
  <p class="ull_course_insufficient_participants">
    <?php echo __('Not enough participants yet', null, 'ullCourseMessages') ?>.
    <?php echo __('A minimum of %min% bookings are necessary', 
      array('%min%' => $doc['min_number_of_participants']), 'ullCourseMessages') ?>.
  </p>
<?php endif ?>  

<?php if ($doc->isFullyBooked()): ?>
  <p class="ull_course_fully_booked">
    <?php echo __('Fully booked', null, 'ullCourseMessages') ?>!
  </p>
<?php endif ?>  
  
<h2><?php echo __('Tariffs', null, 'ullCourseMessages') ?></h2>

<ul>
<?php foreach ($doc->UllCourseTariff as $tariff): ?>
  <li><?php echo $tariff['name'] ?>: <?php echo $tariff['price'] ?></li>
<?php endforeach ?>
</ul> 
  
  
<div class="ull_course_book_me">
  <?php echo link_to(
    __('Book this course', null, 'ullCourseMessages'),
    'ullCourse/selectTariff?slug=' . $doc['slug']
    )
     ?>
</div>  
  
  