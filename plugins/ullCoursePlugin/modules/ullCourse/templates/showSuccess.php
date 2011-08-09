<h1>
  <?php include_component('ullCourse', 'editLink', array('doc' => $doc)) ?>
  <?php echo $doc['name'] ?>
</h1>

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


<p>
  <?php $popupLink = 'ullCourse/trainer?username=' . $doc['Trainer']['username'] ?>
  <?php $popupOptions = array(
    'title' => __('More about %name%', array('%name%' => $doc['Trainer']['display_name']), 'ullCourseMessages'),
    'onclick' => 'this.href="#";popup("' . url_for($popupLink) . '", "Popup", "width=600, height=450,scrollbars=auto,resizable=yes");return false;'
  ) ?>
  <?php echo link_to(
    $doc['Trainer']['display_name'],
    $popupLink,
    $popupOptions
   ) ?>
</p>

<?php $photoWidget = new ullWidgetPhoto() ?>
<p>
  <?php echo link_to(
    $photoWidget->render(null, $doc['Trainer']['photo']),
    $popupLink,
    $popupOptions 
  ) ?>
</p>

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
  <li><?php echo $tariff['name'] ?>: <?php echo format_currency($tariff['price'], 'EUR') ?></li>
<?php endforeach ?>
</ul> 
  
  
<div class="ull_course_book_me">
  <?php echo link_to(
    __('Book this course', null, 'ullCourseMessages'),
    'ullCourse/selectTariff?slug=' . $doc['slug']
    )
     ?>
</div>  
  
  