<?php if ($sf_user->getFlash($name)): ?>
  <div class='flash'><?php echo $sf_user->getFlash($name) ?></div>
<?php endif ?>
