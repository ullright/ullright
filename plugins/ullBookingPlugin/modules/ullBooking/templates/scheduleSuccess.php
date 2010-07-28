<div id="booking_schedule">

  <!-- table and cell grid section -->
  <div id="booking_schedule_content">
    <!-- big date/day of week header -->
    <div class="booking_schedule_day_header">
      <h2>
        <?php echo link_to('&larr;', 'booking_schedule', array('fields[date]' => $previous_day)) ?>
        <?php echo format_datetime($date, 'EEEE') ?>
        <?php echo link_to('&rarr;', 'booking_schedule', array('fields[date]' => $next_day)) ?>
      </h2>
      
      <form id="booking_schedule_select_form" action="<?php echo url_for('booking_schedule') ?>" method="post">
        <?php echo $date_select_form['date']->render(); ?>
        <?php echo submit_tag(__('Display schedule for date', null, 'ullBookingMessages')); ?>
      </form>
      <?php echo $date_select_form['date']->renderError(); ?>
    </div>
    
    <?php include_partial('scheduleGrid',
      array('start_hour' => $start_hour, 'end_hour' => $end_hour, 'cell_status' => $cell_status)); ?>
  </div>
  
  <!-- info section (legend, create, info/delete/edit) -->
  <div id="booking_schedule_info">
    <!-- legend -->
    <h3><?php echo __('Legend', null, 'ullBookingMessages'); ?></h3>
    <table id="booking_schedule_legend">
      <?php for ($i = 0; $i < count($cell_status); $i++) : ?>
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
    
    <!-- info (delete, edit) -->
    <?php if (count($booking_info_list) > 0) : ?> 
      <h3><?php echo __('Bookings', null, 'ullBookingMessages') ?></h3>
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

                var loadForId<?php echo $booking_id ?> = true;
                $('#booking_popup_link_<?php echo $booking_id ?>').hover(function()
                  {
                    if (loadForId<?php echo $booking_id ?>)
                    {
                      $(this).next("div").load('<?php echo url_for('booking_group_list',
                        array('groupName' => $info_entry['bookingGroupName'], 'id' => $booking_id)); ?>',
                        function()
                        {
                          loadForId<?php echo $booking_id ?> = false;
                          //$('#booking_popup_<?php echo $booking_id ?>').stop(true, true).fadeIn();
                          positionPopup($('#booking_popup_<?php echo $booking_id ?>'),
                              $('#booking_popup_link_<?php echo $booking_id ?>'));
                        });
                    }

                    //the 'stop' call prevents rendering problems with repeated hovering                   
                    $(this).next("div").stop(true, true).fadeIn();
                    positionPopup($('#booking_popup_<?php echo $booking_id ?>'),
                      $('#booking_popup_link_<?php echo $booking_id ?>'));
                  },
                  function()
                  {
                      $(this).next("div").animate({opacity: "hide", top: "+=10px"}, "fast");
                  });
              });
              //]]>
              </script>
            <?php endif; ?>
            
            <!-- delete -->
            
            <?php if (UllUserTable::hasPermission('ull_booking_delete')) : ?> 
              <?php 
                $deleteLinks = link_to(__((($isGroup) ? 'Delete this booking only' :
                  'Delete this single booking'), null, 'ullBookingMessages'), 'booking_delete',
                  array('id' => $booking_id, 'viewDate' => $date));
              
                if ($isGroup)
                {
                  $deleteLinks .= ' ' . __('or', null, 'ullBookingMessages') . ' ' .
                    link_to(__('Delete entire group', null, 'ullBookingMessages'),
                    'booking_delete', array('groupName' => $info_entry['bookingGroupName'],
                    'viewDate' => $date));
                }
                
                echo $deleteLinks;
              ?>
            <?php endif; ?>
            
            <br />
            
            <!-- edit -->
            
            <?php if (UllUserTable::hasPermission('ull_booking_edit')) : ?> 
              <?php 
                $editLinks = link_to(__((($isGroup) ? 'Edit this booking only' :
                  'Edit this single booking'), null, 'ullBookingMessages'), 'booking_edit',
                  array('singleId' => $booking_id));
              
                if ($isGroup)
                {
                  $editLinks .= ' ' . __('or', null, 'ullBookingMessages') . ' ' .
                    link_to(__('Edit entire group', null, 'ullBookingMessages'),
                    'booking_edit', array('groupName' => $info_entry['bookingGroupName']));
                }
                
                echo $editLinks;;
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
function positionPopup(control, parent)
{
  control.position({ of: parent, my: 'left center',
    at: 'right center', offset: '30 0', collision: 'fit' });
}

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