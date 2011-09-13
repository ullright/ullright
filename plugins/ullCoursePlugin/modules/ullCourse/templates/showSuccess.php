<div class="cms_content">

<h1>
  <?php include_component('ullCourse', 'editLink', array('doc' => $doc)) ?>
  <?php echo $doc['name'] ?>
</h1>

<div class="ull_course_show_trainer">
  <h2><?php echo __('Trainer', null, 'ullCourseMessages') ?></h2>
  
  <p class="ull_course_show_trainer_name">
    <?php $popupLink = 'ullCourse/trainer?id=' . $doc['Trainer']['id'] ?>
    <?php $popupOptions = array(
      'title' => __('More about %name%', array('%name%' => $doc['Trainer']['display_name']), 'ullCourseMessages'),
      'onclick' => 'this.href="#";popup("' . url_for($popupLink) . '", "Popup", "width=600, height=350,scrollbars=auto,resizable=yes");return false;'
    ) ?>
    <?php echo ull_link_to(
      __('More about', null, 'ullCourseMessages') . ' ' . $doc['Trainer']['first_name'] . ' ' . $doc['Trainer']['last_name'],
      $popupLink,
      array_merge($popupOptions, array('link_new_window' => true))
     ) ?>
  </p>
  
  <?php $photoWidget = new ullWidgetPhoto() ?>
  <p class="ull_course_show_trainer_photo">
    <?php echo link_to(
      $photoWidget->render(null, $doc['Trainer']['photo']),
      $popupLink,
      $popupOptions 
    ) ?>
  </p>
</div>
<!-- end of trainer -->


<div class="ull_course_show_date">
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
</div>
<!-- end of date -->


<div class="ull_course_show_description">
  <h2><?php echo __('Description', null, 'common') ?></h2>
  
  <p><?php echo $doc['description'] ?></p>
</div>


<div class="ull_course_show_tariffs">
  <h2><?php echo __('Tariffs', null, 'ullCourseMessages') ?></h2>
  
  <ul>
  <?php foreach ($doc->UllCourseTariff as $tariff): ?>
    <li><?php echo $tariff['name'] ?>: <?php echo format_currency($tariff['price'], 'EUR') ?></li>
  <?php endforeach ?>
  </ul> 
</div>  


<div class="ull_course_show_booking">
  <h2><?php echo __('Booking', null, 'ullCourseMessages') ?></h2>
  
  <?php if ($doc->is_bookable): ?>
  
    <?php if (!$doc->isFullyBooked()): ?>
      <p>
        <?php echo format_number_choice(
          '[0]|[1]1 spot available|(1,+Inf]%current% spots available', 
          array('%current%' => $doc->getSpotsAvailable()), 
          $doc->getSpotsAvailable(),
          'ullCourseMessages' 
        ) ?>.
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
        <em><?php echo __('Fully booked', null, 'ullCourseMessages') ?>!</em>
      </p>
    <?php endif ?>
  
  
    <?php if (!$doc->isFullyBooked()): ?>  
      <div class="ull_course_show_book">
        <?php echo link_to(
          __('Book this course', null, 'ullCourseMessages'),
          'ullCourse/selectTariff?slug=' . $doc['slug'],
          array('class' => 'ull_course_show_book_link big_button')
          )
           ?>
      </div>  
    <?php endif ?>
    
    
  <?php else: ?>
    <p class="ull_course_show_not_bookable">
      <?php echo __('This course cannot be booked directly. Please refer to the description for details.', null, 'ullCourseMessages') ?>
    </p>
    
  <?php endif ?>
  
</div>
<!-- end of booking -->
  


  

<!-- end of "cms_content" -->  