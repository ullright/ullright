<?php //echo $breadcrumb_tree ?>

<h2><?php echo __('Thank you for signing up', null, 'ullCoreMessages') ?></h2>

<p><?php echo __('You have been logged in and a message containing your account data has been sent to your email address', null, 'ullCoreMessages') ?>.</p>

<p class ="ull_user_signed_up_continue">
  <?php echo link_to(
    __('Continue', null, 'common'),
    $return_url,
    array('class' => 'big_button')
  )?>
</p>
