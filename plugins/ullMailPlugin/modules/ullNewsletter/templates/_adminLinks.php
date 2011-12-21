<?php if (UllUserTable::hasPermission('ull_newsletter_admin')): ?>

  <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
  <ul class="tc_tasks">
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllNewsletterMailingList') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllNewsletterLayout') ?></li>
    <li>
      <?php echo ull_tc_task_link(
        '/ullMailThemeNGPlugin/images/action_icons/admin_24x24', 
        'ullNewsletter/csvImport', 
        __('Recipient import', null, 'ullMailMessages') 
      ) ?>
    </li>
  </ul>

<?php endif ?>    