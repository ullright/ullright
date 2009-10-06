<br /><br />
  <h3>
    <?php echo __('Sorry, but the page you requested was not found.', null, 'common') ?>
  </h3>
  <br />
  <?php echo __('You may find one of the following links useful:', null, 'common') ?>
  
  <ul>
    <li>
    <?php
      echo ull_link_to(__('Welcome page', null, 'common'), '@homepage') .
        ' - ' .  __('find out about ullright', null, 'common');
    ?>
    </li>
    <li>
    <?php
      echo ull_link_to('ullWiki', '/ullWiki') .
        ' - ' . __('search for what you were looking for', null, 'common');
    ?>
    </li>
  </ul>
  <br />
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