<?php 
  $path = sfConfig::get('sf_plugins_dir') . '/ullPhonePlugin/modules/ullOrgchart/templates/listSuccess.php';
  require($path);
?>

<?php echo link_to(__('License information', null, 'custom'), '/uploads/userPhotos/license_information.txt') ?>