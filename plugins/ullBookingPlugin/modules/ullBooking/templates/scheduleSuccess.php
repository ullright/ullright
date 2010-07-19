<div id="booking_schedule">
  
  <?php //TODO: put 'opening hours' into configuration ?>
  <?php $startDisplay = 9; ?>
  <?php $endDisplay = 22; ?>
  
  <!-- table/cell section -->
  <div id="booking_schedule_content">
    
    <!-- big date/day of week header -->
    <div class="booking_schedule_day_header">
      <h2><?php echo link_to('&larr;', 'booking_schedule', array('fields[date]' => $previous_day)) . ' ' .
        ull_format_date($date, false) . ' ' .
        link_to('&rarr;', 'booking_schedule', array('fields[date]' => $next_day)); ?></h2>
      <h3><?php echo format_datetime($date, 'EEEE');?></h3>
    </div>
    
    
    <div id="booking_schedule_cells">  
      
      <!-- header row with characters for resources -->
      <div class="booking_schedule_row">
        <span class="booking_schedule_time_cell booking_schedule_cell">
        </span>
        <?php for ($i = 0; $i < $number_booking_resources; $i++) : ?>
          <span class="booking_schedule_header_cell booking_schedule_cell">
            <?php echo chr($i + ord('A')); ?>
          </span>
        <?php endfor; ?>
      </div>
      
      <!-- data rows -->
      <?php for ($i = $startDisplay * 4; $i < $endDisplay * 4; $i++) : ?>
        <div class="booking_schedule_row">
          <!-- time cell -->
          <?php
            $hours = (int)($i / 4);
            $minutes = $i % 4 * 15;
            $time = (($hours) ? $hours : '00') . ':' . (($minutes) ? $minutes : '00');
          ?>
          <span class="booking_schedule_time_cell booking_schedule_cell"><?php echo $time; ?></span>
          
          <!-- data cell (free/occupied) -->
          <?php for ($j = 0; $j < $number_booking_resources; $j++) : ?>
          
            <?php $occupied = (isset($cell_status[$j][$i])) ? true : false; ?>
            <?php
              $cell_classes  = 'booking_schedule_cell booking_schedule_cell_';
              $cell_classes .= ($occupied) ? 'occupied booking_schedule_cell_' .
                $cell_status[$j][$i]['cellType'] : 'free';
            ?>
            <span class="<?php echo $cell_classes; ?>"
              <?php echo ($occupied) ? 'title="' . $cell_status[$j][$i]['bookingName'] . '"' : ''; ?>>
            </span> 
          <?php endfor; ?>
        </div>
      <?php endfor; ?>
      
      <!-- one additional row with only a single time cell -->
      <div class="booking_schedule_row">
        <?php
          //TODO: put all this time calculation stuff into action/helper, it's the same as above
          $hours = (int)($i / 4);
          $minutes = $i % 4 * 15;
          $time = (($hours) ? $hours : '00') . ':' . (($minutes) ? $minutes : '00');
        ?>
        <span class="booking_schedule_time_cell booking_schedule_cell"><?php echo $time; ?></span>
      </div>
    </div>
  </div>
  
  <!-- info section (select date, legend, create, info/delete) -->
  <div id="booking_schedule_info">
    <h2><?php echo __('Select date', null, 'ullBookingMessages'); ?></h2>
    <form id="booking_schedule_select_form" action="<?php echo url_for('booking_schedule') ?>" method="post">
      <?php echo $date_select_form['date']->render(); ?>
      <?php echo $date_select_form['date']->renderError(); ?>
      <?php echo submit_tag(__('Display schedule for date', null, 'ullBookingMessages')); ?>
    </form>
    
    <!-- legend -->
    <h3><?php echo __('Legend', null, 'ullBookingMessages'); ?></h3>
    <table id="booking_schedule_legend">
      <?php for ($i = 0; $i < $number_booking_resources; $i++) : ?>
        <tr>
          <td class="booking_schedule_legend_char"><?php echo chr($i + ord('A')); ?></td><td>&ndash;</td><td><?php echo $cell_status[$i]['name']; ?></td>
        </tr>
      <?php endfor; ?>
    </table>
    
    <!-- create -->
    <?php if (UllUserTable::hasPermission('ull_booking_create')) : ?> 
      <h3><?php echo __('Create', null, 'common'); ?></h3>
      <?php echo link_to(__('Create new booking', null, 'ullBookingMessages'), 'booking_create'); ?>
    <?php endif; ?>
    
    <!-- info/delete -->
    <?php if (count($booking_info_list) > 0) : ?> 
      <h3><?php echo ('Info'); ?></h3>
      <ul id="booking_delete_list">
        <?php foreach ($booking_info_list as $booking_id => $info_entry) : ?>
          <li>
            <?php echo $info_entry['name'] . ' - '; ?>
            <?php echo $info_entry['range'] . ' - '; ?>
            <?php echo $info_entry['resourceName']; ?>
            <br />
            <?php
              $isGroup = ($info_entry['bookingGroupCount'] > 1) ? true : false;
              $label = ($isGroup) ? __('Recurring booking - there are %count% reservations in this group.',
                array('%count%' => $info_entry['bookingGroupCount']), 'ullBookingMessages') : null;
              echo ($label) ? $label . '<br />' : ''; ?>
            <?php if ($isGroup) : ?>
              <div class="js_mandatory">
                <a href="#" id="booking_popup_link_<?php echo $booking_id ?>">
                  <?php echo __('Show all bookings in this group', null, 'ullBookingMessages'); ?></a>
                <div class="booking_popup" id="booking_popup_<?php echo $booking_id ?>">
                  <?php echo __('Loading', null, 'ullBookingMessages'); ?> ...</div>
              </div>
              
              <script type="text/javascript">
              //<![CDATA[
              $(document).ready(function(){
                //make the link unclickable -> alternative would be to not use a link
                $('#booking_popup_link_<?php echo $booking_id ?>').click(function() { return false; });
                
                $('#booking_popup_link_<?php echo $booking_id ?>').hover(function()
                  {
                    //caches by default
                    $(this).next("div").load('<?php echo url_for('booking_group_list',
                      array('groupName' => $info_entry['bookingGroupName'], 'id' => $booking_id)); ?>');

                    //'stop' prevents rendering problems with repeated hovering                   
                    $(this).next("div").stop(true, true).fadeIn();
                      $('#booking_popup_<?php echo $booking_id ?>').position({
                        of: $('#booking_popup_link_<?php echo $booking_id ?>'),
                        my: 'left top', at: 'left bottom', offset: '4px'
                      });
                  },
                  function()
                  {
                      $(this).next("div").animate({opacity: "hide", top: "+=10px"}, "fast");
                  });
              });
              //]]>
              </script>
            <?php endif; ?>
            
            <?php if (UllUserTable::hasPermission('ull_booking_delete')) : ?> 
              <?php 
                $deleteLinks = link_to(__((($isGroup) ? 'Delete only this booking' :
                  'Delete this single booking'), null, 'ullBookingMessages'), 'booking_delete', array('id' => $booking_id));
              
                if ($isGroup)
                {
                  $deleteLinks .= ' ' . __('or', null, 'ullBookingMessages') . ' ' .
                    link_to(__('Delete entire group', null, 'ullBookingMessages'),
                    'booking_delete', array('groupName' => $info_entry['bookingGroupName']));
                }
                
                echo $deleteLinks;
              ?>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
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