<h3>
  <?php echo __('Sorry, but the page you requested was not found.', null, 'common') ?>
</h3>
  

<?php
  if (sfConfig::has('app_error_404_text'))
  {
    $errorPagesText = __(sfConfig::get('app_error_404_text')); 
    echo auto_link_text($errorPagesText);
  }
  else
  {
    echo auto_link_text(
      __('If you need further assistance, please do not hesitate to contact the helpdesk.', null, 'common'));
  }
?>