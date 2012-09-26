<table class="edit_table">
<tbody>

<?php $old_section = '' ?>
<?php $is_first_time = true; ?>

<?php foreach ($generator->getAutoRenderedColumns() as $column_name => $columns_config): ?>
  
  <?php 
    if (
      // Detect new section
      ($old_section <> $columns_config->getSection() && !$is_first_time) &&
      
      // Ignore sections when set to null
      $columns_config->getSection() !== null
    ):
  ?>
    <tr class="edit_table_spacer_row">
      <td colspan="3"></td>
    </tr>
  <?php endif?>
  
  <?php echo $generator->getForm()->offsetGet($column_name)->renderRow(); ?>

  <?php // Ignore sections when set to null ?>  
  <?php if ($columns_config->getSection() !== null): ?>
    <?php $old_section = $columns_config->getSection() ?>
  <?php endif ?>
  
  <?php $is_first_time = ($is_first_time) ? false : false ?>
    
<?php endforeach?>

</tbody>
</table>

<?php if ($generator->getDefaultAccess() == 'w'):?>
  <dfn><?php echo __('Fields marked with * are mandatory', null, 'ullCoreMessages')?></dfn>
<?php endif ?>


<?php echo javascript_tag('
  $("table.edit_table :input:visible:enabled:first").focus();
  
  markErrorFormFields();
')?>

<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js') ?>
<?php use_javascripts_for_form($generator->getForm()) ?>
<?php use_stylesheets_for_form($generator->getForm()) ?>    