<div id="booking_edit" class="edit_container">
  <h2><?php echo __('Edit ' . (isset($group_name) ? 'group' : 'single') . ' booking', null, 'ullBookingMessages'); ?></h2>

  <?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>
  <?php $routeParam = (isset($group_name)) ? array('groupName' => $group_name) : array('singleId' => $single_id); ?>
  <form id="booking_edit_form" action="<?php echo url_for('booking_edit', $routeParam); ?>" method="post">
    <table class="edit_table">
    <?php foreach ($form as $form_field) : ?>
      <tr>
          <td class="label_column">
            <?php echo $form_field->renderLabel() ?>
          </td>
          <td>
            <?php echo $form_field->render() ?>
            <?php echo $form_field->renderError() ?>
          </td>
        </tr>
    <?php endforeach; ?>

    </table>
    <div class="edit_action_buttons">
      <h3><?php echo __('Actions', null, 'common'); ?></h3>
      <?php
        echo ull_submit_tag(__('Save', null, 'common'));
      ?>
    </div>
  </form>
</div>

<?php if (isset($overlappingBookings)) : ?>
  <div id="overlappingBookingsError">
    <h3 class="form_error"><?php echo __('Overlapping bookings!', null, 'ullBookingMessages'); ?></h3>
    <?php echo __('Could not save changes to the booking.', null, 'ullBookingMessages'); ?><br />
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

<?php use_javascripts_for_form($form); ?>
<?php use_stylesheets_for_form($form); ?>