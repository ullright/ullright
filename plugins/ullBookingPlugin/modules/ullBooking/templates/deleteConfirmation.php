<div id="booking_delete_confirmation">
  <h2><?php echo __('Delete confirmation', null, 'ullBookingMessages'); ?></h2>
  
  <?php echo __('Please confirm the booking deletion.', null, 'ullBookingMessages'); ?>
 
  <div>
    <span id="booking_delete_confirmation_yes">
      <?php echo link_to(__('Delete', null, 'common'), $confirm_url); ?>
    </span>
    <?php echo (__('or', null, 'ullBookingMessages')); ?>
    <span>
      <?php echo link_to(__('Cancel', null, 'common'), $referer_url); ?>
    </span>
  </div>  
</div>