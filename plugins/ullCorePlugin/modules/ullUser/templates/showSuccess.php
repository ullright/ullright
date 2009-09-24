<?php $generator = $sf_data->getRaw('generator') ?>

<div class="edit_container" id="user_popup">

<h1>
  <?php echo $generator->getForm()->offsetGet('first_name')->render() ?>
  <?php echo $generator->getForm()->offsetGet('last_name')->render() ?>
</h1>

<table class="edit_table">
<tbody>

<?php
  foreach ($generator->getForm()->getWidgetSchema()->getPositions() as $column_name)
  {
    if (!in_array($column_name, array('first_name', 'last_name', 'scheduled_update_date')))
    {
      echo $generator->getForm()->offsetGet($column_name)->renderRow();
    }
    
    $ccc = $generator->getColumnsConfig();
    if (isset($ccc[$column_name]) && $ccc[$column_name]->getShowSpacerAfter())
    {
      echo '<tr class="edit_table_spacer_row"><td colspan="3"></td></tr>';
    }
  }
?>

</tbody>
</table>
</div>