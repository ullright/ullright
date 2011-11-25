<?php if (UllUserTable::hasPermission('ull_newsletter_admin')): ?>

  <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
  <ul class="tc_tasks">
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllNewsletterMailingList') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllNewsletterLayout') ?></li>
  </ul>

<?php endif ?>    