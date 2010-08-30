<div id="booking_weekly_schedule">

  <!-- table and cell grid section -->
  <div id="booking_schedule_content">
  
      <div class="booking_schedule_day_header">
      <h3>
        <?php echo link_to('&larr;', 'booking_weekly_schedule', array('fields[date]' => $previous_week)) ?>
        <?php echo __('Week', null, 'common') ?> <?php echo $week ?>
        <?php echo link_to('&rarr;', 'booking_weekly_schedule', array('fields[date]' => $next_week)) ?>
      </h3>
      
      <form id="booking_schedule_select_form" action="<?php echo url_for('booking_weekly_schedule') ?>" method="post">
        <?php echo $date_select_form['date']->render(); ?>
        <?php echo submit_tag(__('Display schedule for week', null, 'ullBookingMessages')); ?>
      </form>
      <?php echo $date_select_form['date']->renderError(); ?>
    </div>
    
    
    
    <div id="booking_schedule_info">
      <!-- legend -->
      <table id="booking_schedule_legend">
        <?php $cell_status = $weekdays[$date] ?>
        <?php for ($i = 0; $i < count($cell_status); $i++) : ?>
          <tr>
            <td class="booking_schedule_legend_char"><?php echo chr($i + ord('A')); ?></td><td>&ndash;</td><td><?php echo $cell_status[$i]['name']; ?></td>
          </tr>
        <?php endfor; ?>
      </table>
    </div>
    
    <div id="booking_schedule_days">

      <?php foreach ($weekdays as $current_date => $weekday): ?>
      
        <div class="booking_schedule_day">
        
          <div class="booking_schedule_day_header">
            <a href="<?php echo url_for('/bookings/fields[date]/' . date('Y-m-d', $current_date)) ?>">
              <h3><?php echo format_datetime($current_date, 'EEEE') ?></h3>
              <?php echo ull_format_date($current_date) ?>
            </a>
          </div>
        
          <?php include_component(
            'ullBooking', 'scheduleGrid', 
            array('cell_status' => $weekday)
          ) ?>
          
        </div>
        
      <?php endforeach?>

    </div>
      
  </div>
</div>

<script type="text/javascript">
//<![CDATA[

$(document).ready(function()
{
  $(document).ready(function()
  {
    $('.js_mandatory').show();
  });
  
  //hide the submit button (auto-submit on date change)
  $('[name="commit"]').hide();

  //submit form automatically if date is selected 
  $("#fields_date").datepicker('option', 'onSelect', function()
  {
    $("#booking_schedule_select_form").submit();
  });
});

//]]>
</script>

<?php use_javascripts_for_form($date_select_form); ?>
<?php use_stylesheets_for_form($date_select_form); ?>