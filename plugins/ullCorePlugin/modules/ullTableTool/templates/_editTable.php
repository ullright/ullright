<table class="edit_table">
<tbody>

<?php
  foreach ($generator->getAutoRenderedColumns() as $column_name => $columns_config)
  {
    echo $generator->getForm()->offsetGet($column_name)->renderRow();
    
    if ($columns_config->getShowSpacerAfter())
    {
      echo '<tr class="edit_table_spacer_row"><td colspan="3"></td></tr>';
    }
  }
?>

</tbody>
</table>