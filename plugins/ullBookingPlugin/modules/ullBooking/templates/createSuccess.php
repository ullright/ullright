<div id="booking_create">
  <h2><?php echo __('Create new booking', null, 'ullBookingMessages'); ?></h2>

  <?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>
  <form id="booking_create_form" action="<?php echo url_for('booking_create') ?>" method="post">
    <table>
    <?php foreach(array('date', 'name', 'time', 'end', 'booking_resource') as $field_name) : ?>
      <tr>
        <td class="label_column">
          <?php echo $form[$field_name]->renderLabel() ?>
        </td>
        <td>
          <?php echo $form[$field_name]->render() ?>
          <?php echo $form[$field_name]->renderError() ?>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php foreach(array('recurring', 'repeats') as $field_name) : ?>
      <tr class="advanced_booking_field">
        <td class="label_column">
          <?php echo $form[$field_name]->renderLabel() ?>
        </td>
        <td>
          <?php echo $form[$field_name]->render() ?>
          <?php echo $form[$field_name]->renderError() ?>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr id="show_advanced_link_row">
      <td>
        <a href="#" id="show_advanced_link" class="js_mandatory">
          <?php echo __('Show advanced options', null, 'ullBookingMessages'); ?>
        </a>
      </td>
    </tr>
    <tr>
      <td>
        <?php
          echo ull_submit_tag(__('Save', null, 'common'),
            array('name' => 'submit|booking_type=simple', 'id' => 'simple_create_button', 'class' => 'js_mandatory'));
        ?>
        <?php
          echo ull_submit_tag(__('Save', null, 'common'),
            array('name' => 'submit|booking_type=advanced', 'class' => 'advanced_booking_field '));
        ?>
        </td>
      </tr>
    </table>
  </form>
</div>

<?php if (isset($overlappingBookings)) : ?>
  <div id="overlappingBookingsError">
    <h3 class="form_error"><?php echo __('Overlapping bookings!', null, 'ullBookingMessages'); ?></h3>
    <?php echo __('Could not save a booking with the dates you selected.', null, 'ullBookingMessages'); ?><br />
    <?php echo __('The resource you selected is already reserved during the following time periods:', null, 'ullBookingMessages'); ?>
    <ul>
      <?php foreach ($overlappingBookings as $overlappingBooking) : ?>
      <li>
        <?php echo $overlappingBooking; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php //deactivated for now because we decided to always show advanced options ?>
<?php if (false) : //if ($is_simple) : ?>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
  $('.advanced_booking_field').hide();
  $('.js_mandatory').show();
  
  $("#show_advanced_link").click(function()
  {
    $('#simple_create_button').hide();
    $("#show_advanced_link_row").fadeOut(function()
    {
      $('.advanced_booking_field').fadeIn();
    });
        
    return false;
  });
});
//]]>  
</script>
<?php endif; ?>

<?php use_javascripts_for_form($form); ?>
<?php use_stylesheets_for_form($form); ?>