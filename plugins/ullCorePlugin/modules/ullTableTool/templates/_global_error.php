<?php if ($form->hasErrors()): ?>
  <div class='form_error form_error_global '>
    <?php echo __('Please correct the following errors', null, 'common') ?>:
    <?php echo $form->renderGlobalErrors() ?>
  </div>  
<?php endif; ?>